<?php
// Selalu mulai sesi di baris paling atas
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autentikasi Admin
if (!isset($_SESSION['username']) || !isset($_SESSION['jenis_login']) || $_SESSION['jenis_login'] != 'admin') {
    // Simpan pesan di session untuk ditampilkan di halaman login
    $_SESSION['pesan_error'] = "Anda harus login sebagai admin untuk mengakses halaman ini.";
    header("location: index.php?page=admin_login&pesan=belum_login");
    exit;
}

include 'koneksi.php';

// Pastikan id_game ada dan merupakan integer
if (isset($_GET['id_game']) && filter_var($_GET['id_game'], FILTER_VALIDATE_INT)) {
    $id_game = (int)$_GET['id_game'];

    // Gunakan Prepared Statements untuk keamanan
    $stmt = mysqli_prepare($connect, "DELETE FROM game WHERE id_game = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id_game);
        if (mysqli_stmt_execute($stmt)) {
            // Jika ada file gambar terkait yang ingin dihapus (misal 'img/game/ID_GAME.jpg')
            // $image_path = "img/game/" . $id_game . ".jpg";
            // if (file_exists($image_path)) {
            //     unlink($image_path); // Hati-hati dengan error handling di sini
            // }
            $_SESSION['pesan_sukses'] = "Game berhasil dihapus!";
        } else {
            $_SESSION['pesan_error'] = "Gagal menghapus game: " . mysqli_stmt_error($stmt);
            error_log("Admin Hapus Game: Gagal execute statement: " . mysqli_stmt_error($stmt));
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['pesan_error'] = "Gagal menyiapkan query hapus game: " . mysqli_error($connect);
        error_log("Admin Hapus Game: Gagal prepare statement: " . mysqli_error($connect));
    }
} else {
    $_SESSION['pesan_error'] = "ID Game tidak valid atau tidak ditemukan.";
}

// Redirect kembali ke halaman data game
// Perbaikan Route
header("Location: index.php?page=admin_data_game");
exit;
?>
