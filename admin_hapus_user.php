<?php  
	include 'koneksi.php';

	$id_user = $_GET['id_user'];
	$query = mysqli_query($connect, "DELETE FROM user where id_user=$id_user");

	if ($query) 
	{
		echo "<script>alert('User Dihapus!');history.go(-1); </script>";

	}
	else
	{
		echo "<script>alert('Hapus User Gagal!');history.go(-1); </script>";

		
	}
