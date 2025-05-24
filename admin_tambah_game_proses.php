<?php 
	include 'koneksi.php';

	$sql    = "SELECT AUTO_INCREMENT FROM information_schema.tables WHERE table_name = 'game' and table_schema = 'final_paweb'";
	$query    = mysqli_query($connect, $sql);

	while ($data = mysqli_fetch_array($query))
	{
	  $nama = "{$data['AUTO_INCREMENT']}.jpg";
	}

	$target_dir = "img/game/";
	$target_file = $target_dir . basename($nama);
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

	$nama_game		= $_POST['nama_game'];
	$nama_dev		= $_POST['nama_dev'];
	$harga			= $_POST['harga'];
	$genre_1		= $_POST['genre_1'];
	$genre_2		= $_POST['genre_2'];
	$genre_3		= $_POST['genre_3'];
	$spek			= $_POST['spek'];
	$tanggal_rilis	= $_POST['tanggal_rilis'];
		
	$sql	= "INSERT INTO game VALUES('','$nama_game', '$nama_dev', '$harga', '$genre_1', '$genre_2', '$genre_3', '$spek', '$tanggal_rilis')";

	$query	= mysqli_query($connect, $sql) or die(mysqli_error($connect));

	// Check if image file is a actual image or fake image
	if(isset($_POST["submit"])) {
	  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
	  if($check !== false) {
	    $uploadOk = 1;
	  } else {
	    echo "File is not an image.";
	    $uploadOk = 0;
	  }
	}

	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ) {
	  echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
	  $uploadOk = 0;
	}

	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
	  echo "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} else {
	  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
	    echo "<script>alert('Game ditambahkan!');window.location='admin_data_game.php' </script>";
	  } else {
	    echo "<script>alert('Tambah Game Gagal!');history.go(-1); </script>";
	  }
	}
 ?>

 

