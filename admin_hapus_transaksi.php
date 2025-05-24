<?php  
	include 'koneksi.php';

	$id_transaksi = $_GET['id_transaksi'];
	$query = mysqli_query($connect, "DELETE FROM transaksi where id_transaksi=$id_transaksi");

	if ($query) 
	{
		echo "<script>alert('Hapus berhasil!');history.go(-1); </script>";
	}
	else
	{
		echo "<script>alert('Registrasi Gagal!');history.go(-1); </script>";
	}
?>