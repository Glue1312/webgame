<?php 
	session_start();
	include 'koneksi.php';
	$username = $_SESSION['username'];

	$query = mysqli_query($connect, "SELECT * FROM user WHERE username like '%$username%'");
	$data = mysqli_fetch_array($query);
	
	$id_user		= $data['id_user'];
	$id_game		= $_POST['id_game'];
	$nama_game		= $_POST['nama_game'];
	$harga			= $_POST['harga'];
	$key		= "LGS" . $id_user . $id_game;

	$sql	= "INSERT INTO transaksi VALUES('','$id_user', '$id_game' ,'$nama_game', '$harga', NOW(), '')";
	$query	= mysqli_query($connect, $sql) or die(mysqli_error($connect));
	$last_id = mysqli_insert_id($connect);


	$key		= "LGS"  . $last_id . $id_user . $id_game;
	$sql	= "UPDATE `transaksi` SET `key` = '$key' WHERE `transaksi`.`id_transaksi` = $last_id";
	$query	= mysqli_query($connect, $sql) or die(mysqli_error($connect));

	if ($query) 
	{
		sleep(5);
		echo "<script>alert('Pembelian Game Berhasil!');window.location='user_games.php' </script>";
	}
	else
	{
		echo "Pembelian Game Gagal";
	}
 ?>
