<?php
session_start();
include 'koneksi.php'; // Pastikan file ini sudah dikonfigurasi untuk Cloud SQL

// Aktifkan pelaporan error untuk debugging (nonaktifkan di produksi)
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username_input = $_POST['username'];
        $password_input = $_POST['password'];

        // Sanitasi input username sebelum digunakan dalam query
        $username_sanitized = mysqli_real_escape_string($connect, $username_input);

        // Ambil data user dari database berdasarkan username
        $sql = "SELECT id_user, username, password FROM user WHERE username = '$username_sanitized'";
        $query = mysqli_query($connect, $sql);

        if ($query) {
            if (mysqli_num_rows($query) == 1) {
                $data_user = mysqli_fetch_assoc($query); // Ambil data sebagai array asosiatif

                // Verifikasi password
                // Ini mengasumsikan password di database sudah di-hash menggunakan password_hash()
                if (password_verify($password_input, $data_user['password'])) { //
                    // Password cocok, set session
                    $_SESSION['id_user'] = $data_user['id_user'];
                    $_SESSION['username'] = $data_user['username'];
                    $_SESSION['status'] = "Login";
                    $_SESSION['jenis_login'] = "user"; // Menandakan ini adalah sesi user biasa
                    header("location: index.php?page=user_dashboard");
                    exit;
                } else {
                    // Password tidak cocok
                    header("location: index.php?page=login&pesan=gagal_password"); // Pesan error lebih spesifik
                    exit;
                }
            } else {
                // Username tidak ditemukan
                header("location: index.php?page=login&pesan=gagal_username"); // Pesan error lebih spesifik
                exit;
            }
        } else {
            // Error saat menjalankan query
            error_log("Login Proses Error - MySQL Query Failed: " . mysqli_error($connect));
            header("location: index.php?page=login&pesan=error_db");
            exit;
        }
    } else {
        // Username atau password tidak dikirim
        header("location: index.php?page=login&pesan=input_kosong");
        exit;
    }
} else {
    // Bukan metode POST, redirect ke halaman login
    header("location: index.php?page=login");
    exit;
}
?>
