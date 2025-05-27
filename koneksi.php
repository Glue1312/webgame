<?php
	$hostname	= "gamebos"; //bawaan
	$username	= "ester"; //bawaan
	$password	= ""; //kosong
	$database	= "gamebosdb"; //nama database yang akan dikoneksikan

	$connect	= new mysqli($hostname, $username, $password, $database); //query koneksi

	if($connect->connect_error) { //cek error
		die("Error : ".$connect->connect_error);
	}
?>
