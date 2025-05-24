<?php 
	include 'koneksi.php';

	$username		= $_POST['username'];
	$password		= $_POST['password'];
	$email			= $_POST['email'];
	$no_telp		= $_POST['no_telp'];

	$sql    = "SELECT * FROM user";
    $query    = mysqli_query($connect, $sql);

    while ($data = mysqli_fetch_array($query)) 
    {
    	if($data['username'] == $username)
    	{
    		echo "<script>alert('Username Sudah Digunakan!');history.go(-1); </script>";
    	}    
    }                   


	$sql	= "INSERT INTO user VALUES('','$username', '$password' ,'$email','$no_telp', NOW())";

	$query	= mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$last_id = mysqli_insert_id($connect);

	if ($query) 
	{
		echo "<script>alert('Registrasi Berhasil! No Id kamu $last_id');window.location='login.php' </script>";
	}
	else
	{
		echo "<script>alert('Registrasi Gagal!');history.go(-1); </script>";
	}
?>