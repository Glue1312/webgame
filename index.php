<?php
// index.php

// Mulai sesi di paling atas, penting untuk autentikasi dan state lainnya
session_start();

// --- Konfigurasi Dasar ---
define('BASE_PATH', __DIR__ . '/'); // Mendefinisikan path dasar aplikasi

// --- Mendapatkan Halaman yang Diminta ---
$default_page_logged_out = 'login'; // Halaman default jika belum login
$default_page_logged_in  = 'admin_dashboard'; // Halaman default jika sudah login

// Ambil nilai 'page' dari query string URL
$page_param = isset($_GET['page']) ? trim($_GET['page']) : null;

// Tentukan halaman yang akan dimuat berdasarkan status login jika parameter 'page' kosong
if (empty($page_param)) {
    if (isset($_SESSION['user_id'])) { // Asumsikan 'user_id' diset di sesi setelah login berhasil
        $page = $default_page_logged_in;
    } else {
        $page = $default_page_logged_out;
    }
} else {
    $page = $page_param;
}

// --- Halaman yang Membutuhkan Autentikasi ---
// Daftar 'key' dari $allowed_pages yang memerlukan login untuk diakses
$auth_required_pages = [
    'admin_dashboard',
    'admin_data_game',
    'admin_data_transaksi',
    'admin_edit_game',
    'admin_edit_game_proses',
    'admin_hapus_game',
    'admin_hapus_transaksi',
    'admin_hapus_user',
    'admin_tambah_game',
    'admin_tambah_game_proses',
    'admin_tambah_user',
    'admin_tambah_user_proses',
    // Tambahkan halaman admin lainnya yang memerlukan login di sini
];

// --- Logika Autentikasi Sederhana ---
// Periksa apakah halaman yang diminta memerlukan autentikasi dan apakah pengguna sudah login
if (in_array($page, $auth_required_pages) && !isset($_SESSION['user_id'])) {
    // Jika halaman butuh login dan user belum login, redirect ke halaman login
    // Sertakan halaman tujuan agar bisa kembali setelah login berhasil
    $redirect_url = 'index.php?page=login';
    if ($page_param) { // Hanya tambahkan redirect_to jika halaman diminta secara eksplisit
        $redirect_url .= '&redirect_to=' . urlencode($page_param);
    }
    header('Location: ' . $redirect_url);
    exit; // Pastikan skrip berhenti setelah redirect
}

// --- Routing Sederhana ---
// Daftar halaman yang valid dan file PHP yang sesuai.
// 'key' adalah apa yang akan Anda gunakan di URL (misal, index.php?page=key)
// 'value' adalah nama file PHP aktual yang akan di-include.
// Sesuaikan daftar ini dengan nama file PHP yang Anda miliki di proyek.
$allowed_pages = [
    'login'                     => 'login.php',
    'login_proses'              => 'admin_login_proses.php', // File untuk memproses login
    'logout'                    => 'logout.php',             // File untuk proses logout

    // Halaman Admin (sesuaikan dengan nama file Anda)
    'admin_dashboard'           => 'admin_dashboard.php',
    'admin_data_game'           => 'admin_data_game.php',
    'admin_data_transaksi'      => 'admin_data_transaksi.php',

    'admin_tambah_game'         => 'admin_tambah_game.php',
    'admin_tambah_game_proses'  => 'admin_tambah_game_proses.php',
    'admin_edit_game'           => 'admin_edit_game.php',
    'admin_edit_game_proses'    => 'admin_edit_game_proses.php',
    'admin_hapus_game'          => 'admin_hapus_game.php', // Biasanya file proses

    'admin_tambah_user'         => 'admin_tambah_user.php',
    'admin_tambah_user_proses'  => 'admin_tambah_user_proses.php',
    'admin_hapus_user'          => 'admin_hapus_user.php',    // Biasanya file proses

    'admin_hapus_transaksi'     => 'admin_hapus_transaksi.php', // Biasanya file proses
    
    // Anda bisa menambahkan halaman lain di sini, misalnya:
    // 'profil_pengguna'        => 'user_profile.php',
];

// --- Memuat Halaman atau File Proses ---
if (array_key_exists($page, $allowed_pages)) {
    $page_file_path = BASE_PATH . $allowed_pages[$page];

    if (file_exists($page_file_path)) {
        // Anda mungkin ingin memiliki template header dan footer umum yang di-include di sini
        // if (should_show_layout($page)) { // Fungsi helper untuk cek apakah layout perlu ditampilkan
        //    include BASE_PATH . 'templates/header.php';
        // }

        include $page_file_path; // Ini akan menjalankan/memuat file PHP yang dituju

        // if (should_show_layout($page)) {
        //    include BASE_PATH . 'templates/footer.php';
        // }
    } else {
        // Error: File halaman tidak ditemukan (kesalahan konfigurasi atau file hilang)
        http_response_code(500); // Internal Server Error
        echo "Error: File untuk halaman '<strong>" . htmlspecialchars($page) . "</strong>' tidak dapat ditemukan (expected: " . htmlspecialchars($allowed_pages[$page]) . ").";
        error_log("Controller error: File not found for page '{$page}'. Expected '{$allowed_pages[$page]}'"); // Catat di log server
    }
} else {
    // Halaman tidak ada dalam daftar $allowed_pages (tidak valid)
    http_response_code(404); // Not Found
    // Anda bisa membuat file 404.php khusus dan meng-include-nya di sini
    // include BASE_PATH . '404.php'; 
    echo "Error 404: Halaman '<strong>" . htmlspecialchars($page) . "</strong>' tidak ditemukan.";
}

/*
// Contoh fungsi helper (opsional, letakkan di atas atau di file terpisah yang di-include)
// Untuk menentukan apakah halaman ini adalah halaman "proses" yang tidak butuh layout HTML
function should_show_layout($page_key) {
    // Halaman proses biasanya tidak menampilkan HTML/layout
    if (strpos($page_key, '_proses') !== false || in_array($page_key, ['logout'])) {
        return false;
    }
    // Halaman lain yang mungkin tidak butuh layout bisa ditambahkan di sini
    return true;
}
*/
?>
