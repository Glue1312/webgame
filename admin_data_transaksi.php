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
    <title>Transaksi LGS - Admin</title> {/* Judul diubah agar lebih deskriptif */}
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
                                <li class="active"><a href="index.php?page=admin_data_transaksi">Transaksi</a></li>
                                <li><a href="index.php?page=admin_data_user">User</a></li>
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
                                    <h4>Data Transaksi</h4>
                                </div>
                            </div>
                            {/* Tidak ada tombol tambah untuk transaksi dari sisi admin di kode asli, jadi dibiarkan */}
                        </div>

                        <div class="table-responsive"> {/* Membuat tabel responsif */}
                            <table class="table table-secondary table-striped">
                                <thead>
                                    <tr>
                                        <th>Id Transaksi</th>
                                        <th>Id User</th>
                                        <th>Id Game</th>
                                        <th>Nama Game</th>
                                        <th>Harga</th>
                                        <th>Tanggal Transaksi</th>
                                        <th>Key</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sql_transaksi = "SELECT id_transaksi, id_user, id_game, nama_game, harga, tanggal_transaksi, `key` FROM transaksi ORDER BY tanggal_transaksi DESC"; // Tambah order by
                                $query_transaksi = mysqli_query($connect, $sql_transaksi);

                                if ($query_transaksi && mysqli_num_rows($query_transaksi) > 0) {
                                    while ($data_transaksi = mysqli_fetch_assoc($query_transaksi)) {
                                ?>
                                    <tr>
                                        {/* Pencegahan XSS untuk semua data yang ditampilkan */}
                                        <td><?php echo htmlspecialchars($data_transaksi['id_transaksi']); ?></td>
                                        <td><?php echo htmlspecialchars($data_transaksi['id_user']); ?></td>
                                        <td><?php echo htmlspecialchars($data_transaksi['id_game']); ?></td>
                                        <td><?php echo htmlspecialchars($data_transaksi['nama_game']); ?></td>
                                        <td>Rp. <?php echo number_format($data_transaksi['harga']); // Format harga ?></td>
                                        <td><?php echo htmlspecialchars(date('d M Y, H:i:s', strtotime($data_transaksi['tanggal_transaksi']))); // Format tanggal ?></td>
                                        <td><?php echo htmlspecialchars($data_transaksi['key']); ?></td>
                                        <td>
                                            {/* Perbaikan Route & URL Encoding & Konfirmasi Hapus */}
                                            <a href="index.php?page=admin_hapus_transaksi&id_transaksi=<?php echo urlencode($data_transaksi['id_transaksi']); ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');">Hapus</a>
                                        </td>
                                    </tr>
                                <?php
                                    }
                                } else {
                                    if (!$query_transaksi) {
                                        error_log("Admin Data Transaksi: Gagal mengambil data transaksi: " . mysqli_error($connect));
                                    }
                                    echo "<tr><td colspan='8' class='text-center'>Tidak ada data transaksi.</td></tr>";
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
            <a href="#" id="scrollToTopButton"><span class="arrow_carrot_up"></span></a>
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
