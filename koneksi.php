<?php
// Ambil dari environment variable atau gunakan default jika tidak diset
$db_socket = getenv('DB_SOCKET') ?: '/cloudsql/e-03-452916:us-central1:gamebos'; // Sesuaikan dengan Instance connection name Anda
$db_name = getenv('DB_NAME') ?: 'gamebosdb'; // Nama database Anda dari pa_web.sql
$db_user = getenv('DB_USER') ?: 'ester';       // Username database Anda
$db_pass = getenv('DB_PASS') ?: 'PASSWORD_KUAT_ANDA'; // GANTI DENGAN PASSWORD KUAT YANG SUDAH ANDA BUAT

$connect = new mysqli(null, $db_user, $db_pass, $db_name, null, $db_socket);

if ($connect->connect_error) {
    // Sebaiknya log error ini daripada menampilkannya langsung ke pengguna di produksi
    error_log("Koneksi Gagal: " . $connect->connect_error);
    die("Koneksi database gagal. Silakan coba lagi nanti."); // Pesan umum untuk pengguna
}
?>
