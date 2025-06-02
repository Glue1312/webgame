<?php
// Pastikan session_start() ada jika Anda berencana menggunakan sesi di halaman ini,
// meskipun untuk proses registrasi murni mungkin tidak selalu dibutuhkan di file proses ini.
// Jika tidak ada penggunaan $_SESSION di sini, Anda bisa menghapusnya.
// session_start();

include 'koneksi.php'; // Pastikan ini adalah file koneksi.php yang sudah disesuaikan untuk Cloud SQL

// Aktifkan pelaporan error PHP untuk debugging (hapus atau nonaktifkan di produksi)
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil dan sanitasi input dasar (minimal escaping untuk mysqli)
    $username = mysqli_real_escape_string($connect, $_POST['username']);
    $password = $_POST['password']; // Akan di-hash
    $email    = mysqli_real_escape_string($connect, $_POST['email']);
    $no_telp  = mysqli_real_escape_string($connect, $_POST['no_telp']);

    // Validasi input (contoh sederhana, Anda bisa menambahkan lebih banyak)
    if (empty($username) || empty($password) || empty($email) || empty($no_telp)) {
        echo "<script>alert('Semua field harus diisi!');history.go(-1);</script>";
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Format email tidak valid!');history.go(-1);</script>";
        exit;
    }

    // Cek apakah username sudah ada
    $sql_check_username = "SELECT id_user FROM user WHERE username = '$username'";
    $query_check_username = mysqli_query($connect, $sql_check_username);

    if (!$query_check_username) {
        // Error saat query cek username
        error_log("Error - Cek Username Gagal: " . mysqli_error($connect));
        echo "<script>alert('Terjadi kesalahan pada database. Silakan coba lagi.');history.go(-1);</script>";
        exit;
    }

    if (mysqli_num_rows($query_check_username) > 0) {
        echo "<script>alert('Username Sudah Digunakan!');history.go(-1);</script>";
        exit;
    }

    // **PENTING: HASH PASSWORD ANDA!**
    // Jangan pernah menyimpan password sebagai plain text.
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    if ($hashed_password === false) {
        error_log("Password hashing failed for username: " . $username);
        echo "<script>alert('Terjadi kesalahan sistem. Silakan coba lagi.');history.go(-1);</script>";
        exit;
    }

    // Gunakan NULL untuk kolom auto-increment
    $sql_insert = "INSERT INTO user (id_user, username, password, email, no_telp, tanggal_dibuat) 
                   VALUES (NULL, '$username', '$hashed_password', '$email', '$no_telp', NOW())";

    $query_insert = mysqli_query($connect, $sql_insert);

    if ($query_insert) {
        $last_id = mysqli_insert_id($connect);
        // Redirect ke halaman login setelah registrasi berhasil
        echo "<script>alert('Registrasi Berhasil! ID User Anda adalah $last_id. Silakan login.');window.location='index.php?page=login'</script>";
        exit;
    } else {
        // Catat error database ke log server (jangan tampilkan mysqli_error ke pengguna di produksi)
        error_log("Registrasi Gagal - MySQL Error: " . mysqli_error($connect) . " | Query: " . $sql_insert);
        echo "<script>alert('Registrasi Gagal! Terjadi kesalahan. Silakan coba lagi atau hubungi administrator.');history.go(-1);</script>";
        exit;
    }

} else {
    // Jika bukan metode POST, redirect atau tampilkan error
    header("location: index.php?page=registrasi");
    exit;
}

// Tutup koneksi jika sudah tidak digunakan lagi di akhir skrip
// mysqli_close($connect); // Mungkin tidak perlu jika skrip langsung exit
?>
