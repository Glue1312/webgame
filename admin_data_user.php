<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username']) || !isset($_SESSION['jenis_login']) || $_SESSION['jenis_login'] != 'admin') {
    header("location: index.php?page=admin_login&pesan=belum_login");
    exit;
}

include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Anime Template">
    <meta name="keywords" content="Anime, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User LGS - Admin</title> {/* Judul diubah */}
    <link rel="shortcut icon" href="img/1.png">

    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="css/plyr.css" type="text/css">
    <link rel="stylesheet" href="css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
</head>

<body>
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <header class="header">
        <div class="container">
            <div class="row">
                <div class="col-lg-2">
                    <div class="header__logo">
                        <a href="index.php?page=admin_dashboard">
                            <img src="img/1.png" alt="Logo Toko">
                        </a>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="header__nav">
                        <nav class="header__menu mobile-menu">
                            <ul>
                                <li><a href="index.php?page=admin_dashboard">Homepage</a></li>
                                <li><a href="index.php?page=admin_data_game">Games </a></li>
                                <li><a href="index.php?page=admin_data_transaksi">Transaksi</a></li>
                                <li class="active"><a href="index.php?page=admin_data_user">User</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="header__nav ms-auto">
                        <nav class="header__menu mobile-menu">
                            <ul>
                                <li><a href="#">Hallo <?php echo htmlspecialchars($_SESSION['username']); // XSS Prevention ?> <span class="arrow_carrot-down"></span></a>
                                    <ul class="dropdown">
                                        <li><a href="index.php?page=logout">Logout</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div id="mobile-menu-wrap"></div>
    </header>
    <section class="product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="trending__product">
                        <div class="row">
                             <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="section-title">
                                    <h4>Data User</h4>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 text-right">
                                 {/* Perbaikan Route untuk tombol Tambah User */}
                                <a href="index.php?page=admin_tambah_user" class="primary-btn mb-4" ><b>Tambah User</b></a>
                            </div>
                        </div>

                        <div class="table-responsive"> {/* Membuat tabel responsif */}
                            <table class="table table-secondary table-striped">
                                <thead>
                                    <tr>
                                        <th>Id User</th>
                                        <th>Username</th>
                                        <th>Password (Hashed)</th> {/* Mengindikasikan password seharusnya di-hash */}
                                        <th>Email</th>
                                        <th>No Telp</th>
                                        <th>Tanggal Buat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sql_user = "SELECT id_user, username, password, email, no_telp, tanggal_dibuat FROM user ORDER BY tanggal_dibuat DESC";
                                $query_user = mysqli_query($connect, $sql_user);

                                if ($query_user && mysqli_num_rows($query_user) > 0) {
                                    while ($data_user = mysqli_fetch_assoc($query_user)) {
                                ?>
                                    <tr>
                                        {/* Pencegahan XSS untuk semua data yang ditampilkan */}
                                        <td><?php echo htmlspecialchars($data_user['id_user']); ?></td>
                                        <td><?php echo htmlspecialchars($data_user['username']); ?></td>
                                        <td><?php echo htmlspecialchars(substr($data_user['password'], 0, 10) . '...'); // Tampilkan sebagian kecil hash atau indikator saja ?></td>
                                        <td><?php echo htmlspecialchars($data_user['email']); ?></td>
                                        <td><?php echo htmlspecialchars($data_user['no_telp']); ?></td>
                                        <td><?php echo htmlspecialchars(date('d M Y, H:i:s', strtotime($data_user['tanggal_dibuat']))); // Format tanggal ?></td>
                                        <td>
                                            {/* Perbaikan Route & URL Encoding & Konfirmasi Hapus */}
                                            <a href="index.php?page=admin_hapus_user&id_user=<?php echo urlencode($data_user['id_user']); ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini? Ini juga akan menghapus transaksi terkait user ini.');">Hapus</a>
                                        </td>
                                    </tr>
                                <?php
                                    }
                                } else {
                                     if (!$query_user) {
                                        error_log("Admin Data User: Gagal mengambil data user: " . mysqli_error($connect));
                                    }
                                    echo "<tr><td colspan='7' class='text-center'>Tidak ada data user.</td></tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer class="footer">
        <div class="page-up">
            <a href="#" id="scrollToTopButton"><span class="arrow_carrot-up"></span></a>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="footer__logo">
                        <a href="index.php?page=admin_dashboard"><img src="img/1.png" alt="Logo Footer"></a>
                    </div>
                </div>
                <div class="col-lg-6">
                    {/* Navigasi footer bisa ditambahkan di sini jika perlu */}
                </div>
                <div class="col-lg-3">
                    <p>
                        Copyright &copy; <script>document.write(new Date().getFullYear());</script>
                    </p>
                </div>
            </div>
        </div>
    </footer>
    <div class="search-model">
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="search-close-switch"><i class="icon_close"></i></div>
            <form class="search-model-form">
                <input type="text" id="search-input" placeholder="Search here.....">
            </form>
        </div>
    </div>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/player.js"></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <script src="js/mixitup.min.js"></script>
    <script src="js/jquery.slicknav.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
