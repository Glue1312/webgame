<?php
// index.php

// Mulai sesi di paling atas, penting untuk autentikasi dan state lainnya
session_start();

// --- Konfigurasi Dasar ---
define('BASE_PATH', __DIR__ . '/'); // Mendefinisikan path dasar aplikasi

// --- Mendapatkan Halaman yang Diminta ---
$default_page_logged_out_user = 'login'; // Halaman default user jika belum login
$default_page_logged_out_admin = 'admin_login'; // Halaman default admin jika belum login
$default_page_logged_in_user  = 'user_dashboard'; // Halaman default user jika sudah login
$default_page_logged_in_admin  = 'admin_dashboard'; // Halaman default admin jika sudah login

// Ambil nilai 'page' dari query string URL
$page_param = isset($_GET['page']) ? trim($_GET['page']) : null;
$page = $page_param;

// Tentukan halaman yang akan dimuat berdasarkan status login jika parameter 'page' kosong
if (empty($page_param)) {
    if (isset($_SESSION['jenis_login']) && $_SESSION['jenis_login'] == 'admin' && isset($_SESSION['username'])) {
        $page = $default_page_logged_in_admin;
    } elseif (isset($_SESSION['id_user']) && isset($_SESSION['username'])) { // Menggunakan id_user untuk user biasa
        $page = $default_page_logged_in_user;
    } else {
        // Jika tidak ada sesi aktif sama sekali, arahkan ke login umum
        $page = $default_page_logged_out_user;
    }
}

// --- Halaman yang Membutuhkan Autentikasi ---
// Daftar 'key' dari $allowed_pages yang memerlukan login ADMIN untuk diakses
$admin_auth_required_pages = [
    'admin_dashboard',
    'admin_data_game',
    'admin_data_transaksi',
    'admin_data_user', // Pastikan ini ada
    'admin_edit_game',
    'admin_edit_game_proses',
    'admin_hapus_game',
    'admin_hapus_transaksi',
    'admin_hapus_user',
    'admin_tambah_game',
    'admin_tambah_game_proses',
    'admin_tambah_user',
    'admin_tambah_user_proses',
];

// Daftar 'key' dari $allowed_pages yang memerlukan login USER BIASA untuk diakses
$user_auth_required_pages = [
    'user_dashboard',
    'user_games',
    'user_edit',
    'user_edit_proses',
    'user_view_game',
    'user_transaksi_beli',
    'user_transaksi_beli_cek',
    'user_proses_transaksi_beli',
];


// --- Logika Autentikasi ---
if (in_array($page, $admin_auth_required_pages)) {
    // Cek apakah admin sudah login
    if (!(isset($_SESSION['username']) && isset($_SESSION['jenis_login']) && $_SESSION['jenis_login'] == 'admin')) {
        $redirect_url = 'index.php?page=admin_login&pesan=belum_login';
        if ($page_param) {
            $redirect_url .= '&redirect_to=' . urlencode($page_param);
        }
        header('Location: ' . $redirect_url);
        exit;
    }
} elseif (in_array($page, $user_auth_required_pages)) {
    // Cek apakah user biasa sudah login
    if (!(isset($_SESSION['id_user']) && isset($_SESSION['username']))) { // Menggunakan id_user
        $redirect_url = 'index.php?page=login&pesan=belum_login';
        if ($page_param) {
            $redirect_url .= '&redirect_to=' . urlencode($page_param);
        }
        header('Location: ' . $redirect_url);
        exit;
    }
}


// --- Routing Sederhana ---
$allowed_pages = [
    'login'                     => 'login.php',
    'user_login_proses'         => 'user_login_proses.php',
    'registrasi'                => 'registrasi.php',
    'registrasi_proses'         => 'registrasi_proses.php',
    'logout'                    => 'logout.php',

    // Halaman User
    'user_dashboard'            => 'user_dashboard.php',
    'user_games'                => 'user_games.php',
    'user_edit'                 => 'user_edit.php',
    'user_edit_proses'          => 'user_edit_proses.php',
    'user_view_game'            => 'user_view_game.php',
    'user_transaksi_beli'       => 'user_transaksi_beli.php',
    'user_transaksi_beli_cek'   => 'user_transaksi_beli_cek.php',
    'user_proses_transaksi_beli'=> 'user_proses_transaksi_beli.php',

    // Halaman Admin
    'admin_login'               => 'admin_login.php',
    'admin_login_proses'        => 'admin_login_proses.php',
    'admin_jenis_login'         => 'admin_jenis_login.php',
    'admin_dashboard'           => 'admin_dashboard.php',
    'admin_data_game'           => 'admin_data_game.php',
    'admin_data_transaksi'      => 'admin_data_transaksi.php',
    'admin_data_user'           => 'admin_data_user.php',

    'admin_tambah_game'         => 'admin_tambah_game.php',
    'admin_tambah_game_proses'  => 'admin_tambah_game_proses.php',
    'admin_edit_game'           => 'admin_edit_game.php',
    'admin_edit_game_proses'    => 'admin_edit_game_proses.php',
    'admin_hapus_game'          => 'admin_hapus_game.php',

    'admin_tambah_user'         => 'admin_tambah_user.php',
    'admin_tambah_user_proses'  => 'admin_tambah_user_proses.php',
    'admin_hapus_user'          => 'admin_hapus_user.php',

    'admin_hapus_transaksi'     => 'admin_hapus_transaksi.php',
];

// --- Memuat Halaman atau File Proses ---
if (array_key_exists($page, $allowed_pages)) {
    $page_file_path = BASE_PATH . $allowed_pages[$page];

    if (file_exists($page_file_path)) {
        include $page_file_path;
    } else {
        http_response_code(500);
        echo "Error: File untuk halaman '<strong>" . htmlspecialchars($page) . "</strong>' tidak dapat ditemukan (expected: " . htmlspecialchars($allowed_pages[$page]) . ").";
        error_log("Controller error: File not found for page '{$page}'. Expected '{$allowed_pages[$page]}'");
    }
} else {
    http_response_code(404);
    echo "Error 404: Halaman '<strong>" . htmlspecialchars($page) . "</strong>' tidak ditemukan.";
}
?>
