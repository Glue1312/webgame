<?php
	session_start();
	// include 'koneksi.php'; // Tidak perlu koneksi DB di sini

	$_SESSION['jenis_login'] = $_GET['jenis_login']; // Ambil dari parameter URL

	if(isset($_SESSION['jenis_login']) && $_SESSION['jenis_login'] == 'admin') // Periksa apakah jenis_login diset dan adalah admin
	{
		// Hapus sesi user biasa jika ada, untuk menghindari kebingungan
        unset($_SESSION['id_user']);
        // unset($_SESSION['username']); // Hapus username user biasa jika ada, atau biarkan jika username admin sama
        // unset($_SESSION['status']); // Hapus status login user biasa

		$_SESSION['status'] = "PendingAdminLogin"; // Status sementara sebelum admin benar-benar login
		header("location: index.php?page=admin_login"); // Arahkan ke halaman login admin via index.php
		exit;
	}
	else
	{
		// Jika jenis_login bukan admin atau tidak diset, kembali ke login user biasa
		header("location: index.php?page=login&pesan=gagal_admin_type");
		exit;
	}
?>
