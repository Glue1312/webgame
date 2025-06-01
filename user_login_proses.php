<?php
	session_start();
	include 'koneksi.php';

	$username = $_POST['username'];
	$password = $_POST['password'];

	$query = mysqli_query($connect, "SELECT * FROM user where username='$username' and password='$password'") or die (mysqli_error($connect));
	$cek = mysqli_num_rows($query);

    $id_user_val = null; // Inisialisasi
    if ($cek > 0) {
        $data = mysqli_fetch_array($query);
    	$id_user_val = $data['id_user'];
    }

	if($cek > 0)
	{
		$_SESSION['id_user'] = $id_user_val; // Menggunakan variabel yang sudah dicek
		$_SESSION['username'] = $username;
		$_SESSION['status'] = "Login"; // Status umum
        $_SESSION['jenis_login'] = "user"; // Tandai sebagai user biasa
		header("location: index.php?page=user_dashboard"); // Perubahan di sini
	}
	else
	{
		header("location: index.php?page=login&pesan=gagal"); // Perubahan di sini
	}
?>
