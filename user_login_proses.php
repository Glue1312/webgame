<?php 
	session_start();
	include 'koneksi.php';

	$username = $_POST['username'];
	$password = $_POST['password'];

	$query = mysqli_query($connect, "SELECT * FROM user where username='$username' and password='$password'") or die (mysqli_error($connect));
	$cek = mysqli_num_rows($query);

    while ($data = mysqli_fetch_array($query)) 
    {
    	$id_user = $data['id_user'];
    }

	if($cek > 0)
	{
		$_SESSION['id_user'] = $id_user;
		$_SESSION['username'] = $username;
		$_SESSION['status'] = "Login";
		header("location:user_dashboard.php");
	}
	else
	{
		header("location:login.php?pesan=gagal");
	}
?>