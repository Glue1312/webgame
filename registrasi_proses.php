<?php
	include 'koneksi.php';

	$username		= $_POST['username'];
	$password		= $_POST['password'];
	$email			= $_POST['email'];
	$no_telp		= $_POST['no_telp'];

	$sql_check    = "SELECT * FROM user WHERE username = '$username'"; // Query lebih aman
    $query_check  = mysqli_query($connect, $sql_check);

    if (mysqli_num_rows($query_check) > 0) {
        echo "<script>alert('Username Sudah Digunakan!');history.go(-1); </script>";
        exit; // Hentikan eksekusi jika username sudah ada
    }

	$sql	= "INSERT INTO user VALUES('','$username', '$password' ,'$email','$no_telp', NOW())";

	$query	= mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$last_id = mysqli_insert_id($connect);

	if ($query)
	{
		echo "<script>alert('Registrasi Berhasil! No Id kamu $last_id');window.location='index.php?page=login' </script>"; // Perubahan di sini
	}
	else
	{
		echo "<script>alert('Registrasi Gagal!');history.go(-1); </script>";
	}
?>
