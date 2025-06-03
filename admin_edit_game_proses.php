<?php
// Selalu mulai sesi di baris paling atas
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autentikasi Admin
if (!isset($_SESSION['username']) || !isset($_SESSION['jenis_login']) || $_SESSION['jenis_login'] != 'admin') {
    $_SESSION['pesan_error'] = "Akses ditolak. Silakan login sebagai admin.";
    header("location: index.php?page=admin_login");
    exit;
}

include 'koneksi.php'; //

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) { // Memastikan form disubmit

    // Ambil dan validasi id_game
    $id_game = filter_var($_POST['id_game'], FILTER_VALIDATE_INT); //
    if ($id_game === false || $id_game <= 0) {
        $_SESSION['pesan_error_edit_game'] = "ID Game tidak valid.";
        header("Location: index.php?page=admin_data_game"); // Redirect ke data game jika ID tidak valid
        exit;
    }

    // Ambil dan sanitasi data lainnya
    $nama_game      = trim($_POST['nama_game']); //
    $nama_dev       = trim($_POST['nama_dev']); //
    $harga          = filter_var($_POST['harga'], FILTER_VALIDATE_INT); //
    $genre_1        = trim($_POST['genre_1']); //
    $genre_2        = trim($_POST['genre_2']); //
    $genre_3        = trim($_POST['genre_3']); //
    $spek           = trim($_POST['spek']); //
    $tanggal_rilis  = $_POST['tanggal_rilis']; //


    // Validasi dasar
    if (empty($nama_game) || empty($nama_dev) || $harga === false || $harga < 0 || empty($genre_1) || empty($spek) || empty($tanggal_rilis)) {
        $_SESSION['pesan_error_edit_game'] = "Data tidak lengkap atau format harga salah. Semua field wajib kecuali Genre 2 & 3.";
        // Redirect kembali ke halaman edit dengan ID yang benar
        header("Location: index.php?page=admin_edit_game&id_game=" . urlencode($id_game));
        exit;
    }

    // Validasi format tanggal (YYYY-MM-DD)
    if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $tanggal_rilis)) {
        $_SESSION['pesan_error_edit_game'] = "Format tanggal rilis tidak valid. Gunakan YYYY-MM-DD.";
        header("Location: index.php?page=admin_edit_game&id_game=" . urlencode($id_game));
        exit;
    }

    // Query UPDATE menggunakan Prepared Statements
    $sql = "UPDATE game SET nama_game = ?, nama_dev = ?, harga = ?, genre_1 = ?, genre_2 = ?, genre_3 = ?, spek = ?, tanggal_rilis = ? WHERE id_game = ?"; //
    $stmt = mysqli_prepare($connect, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssisssssi", $nama_game, $nama_dev, $harga, $genre_1, $genre_2, $genre_3, $spek, $tanggal_rilis, $id_game);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['pesan_sukses'] = "Data game '" . htmlspecialchars($nama_game) . "' berhasil diupdate!";
            header("Location: index.php?page=admin_data_game");
            exit;
        } else {
            $_SESSION['pesan_error_edit_game'] = "Gagal mengupdate data game: " . mysqli_stmt_error($stmt);
            error_log("Admin Edit Game: Gagal execute statement: " . mysqli_stmt_error($stmt));
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['pesan_error_edit_game'] = "Gagal menyiapkan query update game: " . mysqli_error($connect);
        error_log("Admin Edit Game: Gagal prepare statement: " . mysqli_error($connect));
    }

    // Jika ada error, redirect kembali ke form edit
    header("Location: index.php?page=admin_edit_game&id_game=" . urlencode($id_game));
    exit;

} else {
    // Jika bukan metode POST atau submit tidak ditekan
    $_SESSION['pesan_error'] = "Akses tidak sah atau form tidak disubmit.";
    header("Location: index.php?page=admin_data_game");
    exit;
}
?>
