<?php  
	include 'koneksi.php';

	$id_game = $_GET['id_game'];
	$query = mysqli_query($connect, "DELETE FROM game where id_game=$id_game");

	if ($query) 
	{
		echo "<script>alert('Hapus Berhasil!');history.go(-1); </script>";

	}
	else
	{
		echo "<script>alert('Hapus Gagal!');history.go(-1); </script>";

	}
?>