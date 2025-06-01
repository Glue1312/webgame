<?php
	session_start();
	include 'koneksi.php';

	$username = $_POST['username'];
	$password = $_POST['password'];

	$data = mysqli_query($connect, "SELECT * FROM admin where username='$username' and password='$password'") or die (mysqli_error($connect));
	$cek = mysqli_num_rows($data);

	if($cek > 0)
	{
		$_SESSION['username'] = $username;
		$_SESSION['status'] = "Login"; // Status umum
        $_SESSION['jenis_login'] = "admin"; // Tandai sebagai admin
		header("location: index.php?page=admin_dashboard"); // Perubahan di sini
	}
	else
	{
		header("location: index.php?page=admin_login&pesan=gagal"); // Perubahan di sini
	}
?>
