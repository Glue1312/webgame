<?php
session_start(); // Mulai sesi untuk mengakses variabel sesi
include 'koneksi.php'; // Pastikan ini adalah file koneksi.php yang sudah disesuaikan

// Aktifkan pelaporan error PHP untuk debugging (hapus atau nonaktifkan di produksi)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Pastikan pengguna sudah login sebelum memproses
if (!(isset($_SESSION['id_user']) && isset($_SESSION['username']))) {
    // Jika belum login, redirect ke halaman login
    header("location: index.php?page=login&pesan=belum_login");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi apakah semua data yang diharapkan ada
    if (isset($_POST['id_user']) && isset($_POST['email']) && isset($_POST['no_telp'])) {

        $id_user = $_POST['id_user'];
        // Sanitasi input sebelum digunakan dalam query
        $email   = mysqli_real_escape_string($connect, $_POST['email']);
        $no_telp = mysqli_real_escape_string($connect, $_POST['no_telp']);
        // Username dan password diambil dari sesi atau tidak diubah di sini
        // Jika Anda memperbolehkan perubahan username dan password, Anda harus menambahkan validasi dan hashing untuk password baru.

        // Pastikan id_user yang di-POST adalah sama dengan id_user yang ada di sesi
        // Ini untuk mencegah pengguna mengubah data pengguna lain jika mereka memanipulasi ID di form.
        if ($id_user != $_SESSION['id_user']) {
            echo "<script>alert('Error: Aksi tidak diizinkan!');history.go(-1); </script>";
            exit;
        }

        // Validasi email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Format email tidak valid!');history.go(-1); </script>";
            exit;
        }

        // Validasi nomor telepon (contoh sederhana: hanya angka dan panjang tertentu)
        if (!preg_match('/^[0-9]{10,15}$/', $no_telp)) {
             // Anda bisa membuat validasi yang lebih ketat sesuai kebutuhan
            echo "<script>alert('Format nomor telepon tidak valid!');history.go(-1); </script>";
            exit;
        }

        // Query UPDATE
        // Perhatikan: Username dan password tidak diubah dalam skrip ini.
        // Jika Anda ingin mengizinkan perubahan password, pastikan untuk MENG-HASH password baru tersebut.
        $sql = "UPDATE user SET email = '$email', no_telp = '$no_telp' WHERE id_user = " . intval($id_user);

        $query = mysqli_query($connect, $sql);

        if ($query) {
            // Jika Anda ingin mengupdate juga data sesi jika email berubah (opsional)
            // $_SESSION['email'] = $email; // Jika Anda menyimpan email di sesi

            echo "<script>alert('Edit Data Berhasil!');window.location='index.php?page=user_dashboard'</script>"; // Redirect ke dashboard user
            exit;
        } else {
            // Catat error ke log server
            error_log("User Edit Proses Gagal - MySQL Error: " . mysqli_error($connect) . " | Query: " . $sql);
            echo "<script>alert('Edit Data Gagal! Silakan coba lagi atau hubungi administrator.');history.go(-1); </script>";
            exit;
        }
    } else {
        echo "<script>alert('Data tidak lengkap!');history.go(-1);</script>";
        exit;
    }
} else {
    // Jika bukan metode POST, redirect
    header("location: index.php?page=user_edit"); // Atau ke halaman yang sesuai
    exit;
}

// Tutup koneksi jika perlu (biasanya PHP akan menutupnya otomatis di akhir skrip)
// mysqli_close($connect);
?>
