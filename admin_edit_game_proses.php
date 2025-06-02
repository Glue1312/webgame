<?php
session_start();
include 'koneksi.php';
require __DIR__ . '/vendor/autoload.php';

use Google\Cloud\Storage\StorageClient;

// Pengecekan sesi admin
if (!(isset($_SESSION['username']) && isset($_SESSION['jenis_login']) && $_SESSION['jenis_login'] == 'admin')) {
    echo "<script>alert('Akses ditolak!'); window.location='index.php?page=admin_login';</script>";
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $id_game = intval($_POST['id_game']);
    $nama_game = mysqli_real_escape_string($connect, $_POST['nama_game']);
    $nama_dev = mysqli_real_escape_string($connect, $_POST['nama_dev']);
    $harga = floatval($_POST['harga']);
    $genre_1 = mysqli_real_escape_string($connect, $_POST['genre_1']);
    $genre_2 = mysqli_real_escape_string($connect, $_POST['genre_2']);
    $genre_3 = mysqli_real_escape_string($connect, $_POST['genre_3']);
    $spek = mysqli_real_escape_string($connect, $_POST['spek']);
    $tanggal_rilis = mysqli_real_escape_string($connect, $_POST['tanggal_rilis']);

    // Update data game di database (tanpa mengubah gambar dulu)
    $sql_update_game_data = "UPDATE game SET 
                        nama_game = '$nama_game', 
                        nama_dev = '$nama_dev', 
                        harga = '$harga', 
                        genre_1 = '$genre_1', 
                        genre_2 = '$genre_2', 
                        genre_3 = '$genre_3', 
                        spek = '$spek', 
                        tanggal_rilis = '$tanggal_rilis'
                        WHERE id_game = $id_game";

    $query_update = mysqli_query($connect, $sql_update_game_data);

    if (!$query_update) {
        error_log("Edit Game Gagal (Database Update Error): " . mysqli_error($connect));
        echo "<script>alert('Edit Game Gagal! Terjadi kesalahan database.');history.go(-1);</script>";
        exit;
    }

    // Proses unggah gambar baru jika ada
    if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES["fileToUpload"]["tmp_name"];
        $originalFileName = basename($_FILES["fileToUpload"]["name"]);
        $fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));

        $allowedExtensions = ['jpg', 'jpeg', 'png']; // Hanya JPG, JPEG, PNG
        if (!in_array($fileExtension, $allowedExtensions)) {
            // Data game sudah terupdate, tapi gambar baru tidak valid. Beri pesan.
            echo "<script>alert('Data game berhasil diupdate, tetapi format file gambar baru tidak diizinkan! Hanya JPG, JPEG, PNG.'); window.location='index.php?page=admin_data_game';</script>";
            exit;
        }

        // Nama file di GCS akan menjadi ID game + ekstensi aslinya
        $nama_file_gambar_gcs_baru = "{$id_game}.{$fileExtension}";
        $bucketName = getenv('GCS_BUCKET_NAME');
        if (!$bucketName) {
            error_log("GCS_BUCKET_NAME environment variable not set for edit process.");
            echo "<script>alert('Data game berhasil diupdate, tetapi konfigurasi server error (bucket) untuk gambar.'); window.location='index.php?page=admin_data_game';</script>";
            exit;
        }
        $objectNameBaru = 'img/game/' . $nama_file_gambar_gcs_baru;

        try {
            $storage = new StorageClient();
            $bucket = $storage->bucket($bucketName);

            // Hapus gambar lama dari GCS jika ada dengan ekstensi yang berbeda
            // Atau jika Anda selalu menimpa dengan nama yg sama (misal id_game.jpg), 
            // maka upload akan otomatis menimpa.
            // Untuk kasus ini, kita akan coba hapus semua kemungkinan ekstensi lama sebelum upload yang baru.
            $possibleOldExtensions = ['jpg', 'jpeg', 'png', 'gif']; // Tambahkan gif jika sebelumnya mungkin
            foreach ($possibleOldExtensions as $oldExt) {
                $oldObjectName = 'img/game/' . $id_game . '.' . $oldExt;
                if ($oldObjectName != $objectNameBaru) { // Jangan hapus jika nama dan ekstensinya sama persis
                    $oldObject = $bucket->object($oldObjectName);
                    if ($oldObject->exists()) {
                        $oldObject->delete();
                    }
                }
            }
            
            $fileSource = fopen($fileTmpPath, 'r');
            $bucket->upload($fileSource, [
                'name' => $objectNameBaru, // Nama file baru
                'predefinedAcl' => 'publicRead'
            ]);

            // Karena kita TIDAK menyimpan nama file di DB, tidak perlu UPDATE DB untuk nama gambar.
            // Nama file gambar secara implisit adalah id_game.ekstensi_terbaru

        } catch (Exception $e) {
            error_log("GCS Upload/Delete Error (Edit Game): " . $e->getMessage());
            // Data game sudah terupdate, tapi gambar gagal. Beri tahu pengguna.
            echo "<script>alert('Data game berhasil diupdate, tetapi terjadi error saat memproses gambar baru.'); window.location='index.php?page=admin_data_game';</script>";
            exit;
        }
    } else if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] != UPLOAD_ERR_NO_FILE) {
        // Ada error lain saat upload (bukan karena tidak ada file)
        echo "<script>alert('Data game berhasil diupdate, tetapi terjadi error saat mengunggah file gambar. Kode Error: " . $_FILES["fileToUpload"]["error"] . "'); window.location='index.php?page=admin_data_game';</script>";
        exit;
    }

    // Jika semua berhasil atau tidak ada gambar baru diupload (data teks tetap terupdate)
    echo "<script>alert('Edit Game Berhasil!');window.location='index.php?page=admin_data_game'</script>";
    exit;

} else {
    header("Location: index.php?page=admin_data_game");
    exit;
}
?>
