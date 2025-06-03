<?php
// Selalu mulai sesi di baris paling atas
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autentikasi Admin (sebaiknya ada di setiap file proses admin)
if (!isset($_SESSION['username']) || !isset($_SESSION['jenis_login']) || $_SESSION['jenis_login'] != 'admin') {
    $_SESSION['pesan_error'] = "Akses ditolak. Silakan login sebagai admin.";
    header("location: index.php?page=admin_login");
    exit;
}

include 'koneksi.php'; //

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $username = trim($_POST['username']); //
    $password_plain = $_POST['password']; //
    $email    = trim($_POST['email']); //
    $no_telp  = trim($_POST['no_telp']); //

    // Validasi dasar (bisa ditambahkan validasi yang lebih kompleks)
    if (empty($username) || empty($password_plain) || empty($email) || empty($no_telp)) {
        $_SESSION['pesan_error_tambah_user'] = "Semua field harus diisi!";
        header("Location: index.php?page=admin_tambah_user");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['pesan_error_tambah_user'] = "Format email tidak valid!";
        header("Location: index.php?page=admin_tambah_user");
        exit;
    }

    // Cek apakah username sudah ada
    $stmt_check_username = mysqli_prepare($connect, "SELECT id_user FROM user WHERE username = ?");
    mysqli_stmt_bind_param($stmt_check_username, "s", $username);
    mysqli_stmt_execute($stmt_check_username);
    mysqli_stmt_store_result($stmt_check_username);

    if (mysqli_stmt_num_rows($stmt_check_username) > 0) {
        $_SESSION['pesan_error_tambah_user'] = "Username sudah digunakan!";
        mysqli_stmt_close($stmt_check_username);
        header("Location: index.php?page=admin_tambah_user");
        exit;
    }
    mysqli_stmt_close($stmt_check_username);

    // Hash password sebelum disimpan
    $hashed_password = password_hash($password_plain, PASSWORD_DEFAULT);
    if ($hashed_password === false) {
        $_SESSION['pesan_error_tambah_user'] = "Terjadi kesalahan saat memproses password.";
        error_log("Admin Tambah User: Gagal hashing password untuk username: " . $username);
        header("Location: index.php?page=admin_tambah_user");
        exit;
    }

    // Gunakan Prepared Statements untuk INSERT
    $sql = "INSERT INTO user (username, password, email, no_telp, tanggal_dibuat) VALUES (?, ?, ?, ?, NOW())"; //
    $stmt_insert = mysqli_prepare($connect, $sql);

    if ($stmt_insert) {
        mysqli_stmt_bind_param($stmt_insert, "ssss", $username, $hashed_password, $email, $no_telp);
        if (mysqli_stmt_execute($stmt_insert)) {
            $_SESSION['pesan_sukses'] = "User baru berhasil ditambahkan!";
            header("Location: index.php?page=admin_data_user"); //
            exit;
        } else {
            $_SESSION['pesan_error_tambah_user'] = "Gagal menambahkan user: " . mysqli_stmt_error($stmt_insert);
            error_log("Admin Tambah User: Gagal execute statement: " . mysqli_stmt_error($stmt_insert));
        }
        mysqli_stmt_close($stmt_insert);
    } else {
        $_SESSION['pesan_error_tambah_user'] = "Gagal menyiapkan query tambah user: " . mysqli_error($connect);
        error_log("Admin Tambah User: Gagal prepare statement: " . mysqli_error($connect));
    }
    // Jika sampai sini berarti ada error, redirect kembali ke form tambah
    header("Location: index.php?page=admin_tambah_user");
    exit;

} else {
    // Jika bukan metode POST, redirect ke halaman yang sesuai
    $_SESSION['pesan_error'] = "Akses tidak sah.";
    header("Location: index.php?page=admin_dashboard"); // Atau ke halaman lain yang lebih sesuai
    exit;
}
?>
