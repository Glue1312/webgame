<?php 
	include 'koneksi.php';

	$id_user		= $_POST['id_user'];
	$username		= $_POST['username'];
	$password		= $_POST['password'];
	$email			= $_POST['email'];
	$no_telp		= $_POST['no_telp'];

	$sql	= "UPDATE user SET email = '$email', no_telp = '$no_telp'  WHERE id_user=$id_user";

	$query	= mysqli_query($connect, $sql) or die(mysqli_error($connect));

	if ($query) 
	{
		echo "<script>alert('Edit Berhasil!');window.location='user_dashboard.php' </script>";
	} 
	else 
	{
	    echo "<script>alert('Edit Gagal!');history.go(-1); </script>";;
	}	
	

?>