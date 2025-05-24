<?php 
	session_start();
	include 'koneksi.php';

	$_SESSION['jenis_login'] = $_GET['jenis_login'];
	
	if(($_SESSION['jenis_login'] == 'admin'))
	{
		$_SESSION['status'] = "Login";
		header("location:admin_login.php");
	}
	else
	{
		header("location:login.php?pesan=gagal");
	}
?>