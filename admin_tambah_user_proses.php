<?php 
	include 'koneksi.php';

	$username		= $_POST['username'];
	$password		= $_POST['password'];
	$email			= $_POST['email'];
	$no_telp		= $_POST['no_telp'];
	
	$sql	= "INSERT INTO user VALUES('','$username', '$password' ,'$email','$no_telp', NOW())";

	$query	= mysqli_query($connect, $sql) or die(mysqli_error($connect));

	if ($query) 
	{
		header("Location:admin_data_user.php");
	}
	else
	{
		echo "Tambah User Gagal";
	}
 ?>

