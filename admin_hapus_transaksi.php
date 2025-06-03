<?php
// Selalu mulai sesi di baris paling atas
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autentikasi Admin
if (!isset($_SESSION['username']) || !isset($_SESSION['jenis_login']) || $_SESSION['jenis_login'] != 'admin') {
    $_SESSION['pesan_error'] = "Anda harus login sebagai admin untuk mengakses halaman ini.";
    header("location: index.php?page=admin_login&pesan=belum_login");
    exit;
}

include 'koneksi.php';

// Pastikan id_transaksi ada dan merupakan integer
if (isset($_GET['id_transaksi']) && filter_var($_GET['id_transaksi'], FILTER_VALIDATE_INT)) {
    $id_transaksi = (int)$_GET['id_transaksi'];

    // Gunakan Prepared Statements untuk keamanan
    $stmt = mysqli_prepare($connect, "DELETE FROM transaksi WHERE id_transaksi = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id_transaksi);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['pesan_sukses'] = "Transaksi berhasil dihapus!";
        } else {
            $_SESSION['pesan_error'] = "Gagal menghapus transaksi: " . mysqli_stmt_error($stmt);
            error_log("Admin Hapus Transaksi: Gagal execute statement: " . mysqli_stmt_error($stmt));
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['pesan_error'] = "Gagal menyiapkan query hapus transaksi: " . mysqli_error($connect);
        error_log("Admin Hapus Transaksi: Gagal prepare statement: " . mysqli_error($connect));
    }
} else {
    $_SESSION['pesan_error'] = "ID Transaksi tidak valid atau tidak ditemukan.";
}

// Redirect kembali ke halaman data transaksi
// Perbaikan Route
header("Location: index.php?page=admin_data_transaksi");
exit;
?>
