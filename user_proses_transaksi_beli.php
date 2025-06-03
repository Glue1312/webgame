<?php
// Selalu mulai sesi di baris paling atas
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autentikasi User
if (!(isset($_SESSION['id_user']) && isset($_SESSION['username']))) {
    $_SESSION['pesan_error'] = "Anda harus login untuk melakukan transaksi."; // Pesan untuk halaman login
    header("location: index.php?page=login&pesan=belum_login");
    exit;
}

include 'koneksi.php'; //

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi dan ambil data dari POST
    // Pastikan semua data yang dibutuhkan ada
    if (!isset($_POST['id_game']) || !isset($_POST['nama_game']) || !isset($_POST['harga'])) {
        $_SESSION['pesan_error_transaksi'] = "Data transaksi tidak lengkap.";
        // Redirect kembali ke halaman sebelumnya atau halaman game, perlu cara untuk mengetahui game mana
        // Untuk sederhana, redirect ke dashboard
        header("Location: index.php?page=user_dashboard");
        exit;
    }

    $id_user        = $_SESSION['id_user']; // Diambil dari session, lebih aman
    $id_game        = filter_var($_POST['id_game'], FILTER_VALIDATE_INT); //
    $nama_game      = trim($_POST['nama_game']); //
    $harga          = filter_var($_POST['harga'], FILTER_VALIDATE_INT); //

    if ($id_game === false || $harga === false || empty($nama_game)) {
        $_SESSION['pesan_error_transaksi'] = "Data game tidak valid untuk transaksi.";
        // Redirect kembali, idealnya ke halaman detail game yang bersangkutan
        // Jika id_game valid, kita bisa redirect kembali ke halaman beli
        if ($id_game) {
             header("Location: index.php?page=user_transaksi_beli&id_game=" . urlencode($id_game));
        } else {
             header("Location: index.php?page=user_dashboard");
        }
        exit;
    }

    // Insert transaksi menggunakan Prepared Statements
    $sql_insert_transaksi = "INSERT INTO transaksi (id_user, id_game, nama_game, harga, tanggal_transaksi) VALUES (?, ?, ?, ?, NOW())"; //
    $stmt_insert = mysqli_prepare($connect, $sql_insert_transaksi);

    if ($stmt_insert) {
        mysqli_stmt_bind_param($stmt_insert, "iisi", $id_user, $id_game, $nama_game, $harga);
        if (mysqli_stmt_execute($stmt_insert)) {
            $last_id_transaksi = mysqli_insert_id($connect); //

            // Generate dan update key transaksi
            $key_transaksi = "LGS" . $last_id_transaksi . $id_user . $id_game; //
            $sql_update_key = "UPDATE transaksi SET `key` = ? WHERE id_transaksi = ?"; //
            $stmt_update = mysqli_prepare($connect, $sql_update_key);

            if ($stmt_update) {
                mysqli_stmt_bind_param($stmt_update, "si", $key_transaksi, $last_id_transaksi);
                if (mysqli_stmt_execute($stmt_update)) {
                    // sleep(5); // Sleep mungkin tidak ideal untuk UX, pertimbangkan menghapusnya
                    $_SESSION['pesan_sukses'] = "Pembelian Game '" . htmlspecialchars($nama_game) . "' Berhasil! Key: " . htmlspecialchars($key_transaksi);
                    header("Location: index.php?page=user_games"); // Perbaikan Route
                    exit;
                } else {
                    $_SESSION['pesan_error_transaksi'] = "Pembelian Game Berhasil, tetapi gagal generate key.";
                    error_log("Proses Transaksi: Gagal update key: " . mysqli_stmt_error($stmt_update));
                }
                mysqli_stmt_close($stmt_update);
            } else {
                 $_SESSION['pesan_error_transaksi'] = "Pembelian Game Berhasil, tetapi gagal siapkan update key.";
                 error_log("Proses Transaksi: Gagal prepare update key: " . mysqli_error($connect));
            }
        } else {
            $_SESSION['pesan_error_transaksi'] = "Pembelian Game Gagal: " . mysqli_stmt_error($stmt_insert);
            error_log("Proses Transaksi: Gagal execute insert transaksi: " . mysqli_stmt_error($stmt_insert));
        }
        mysqli_stmt_close($stmt_insert);
    } else {
        $_SESSION['pesan_error_transaksi'] = "Gagal menyiapkan transaksi: " . mysqli_error($connect);
        error_log("Proses Transaksi: Gagal prepare insert transaksi: " . mysqli_error($connect));
    }

    // Jika ada error setelah insert berhasil sebagian atau gagal total, redirect kembali
    // Idealnya ke halaman detail game yang bersangkutan
    if ($id_game) {
         header("Location: index.php?page=user_transaksi_beli&id_game=" . urlencode($id_game));
    } else {
         header("Location: index.php?page=user_dashboard");
    }
    exit;

} else {
    // Jika bukan metode POST
    $_SESSION['pesan_error'] = "Akses tidak sah.";
    header("Location: index.php?page=user_dashboard"); // Atau halaman lain yang lebih sesuai
    exit;
}
?>
