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
		$_SESSION['status'] = "Login";
		header("location:admin_dashboard.php");
	}
	else
	{
		header("location:admin_login.php?pesan=gagal");
	}
?>