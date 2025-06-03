<?php
// Selalu mulai sesi di baris paling atas sebelum output apapun
// Di index.php, session_start() sudah ada, jadi baris ini mungkin tidak diperlukan
// jika file ini selalu di-include melalui index.php.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autentikasi Admin (lapisan tambahan jika file diakses langsung)
if (!isset($_SESSION['username']) || !isset($_SESSION['jenis_login']) || $_SESSION['jenis_login'] != 'admin') {
    header("location: index.php?page=admin_login&pesan=belum_login");
    exit;
}

include 'koneksi.php'; // Pastikan path koneksi.php benar
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Anime Template">
    <meta name="keywords" content="Anime, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Game LGS</title>
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
                                <li class="active"><a href="index.php?page=admin_data_game">Games </a></li>
                                <li><a href="index.php?page=admin_data_transaksi">Transaksi</a></li>
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
                            <div class="col-lg-8 col-md-8 col-sm-8"> <div class="section-title">
                                    <h4>Data Games</h4>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 text-right"> <a href="index.php?page=admin_tambah_game" class="primary-btn mb-4"><b>Tambah Data Game</b></a>
                            </div>
                        </div>

                        <div class="row">
                            <?php
                            $sql_games = "SELECT id_game, nama_game, nama_dev, harga, genre_1, genre_2, genre_3, spek, tanggal_rilis FROM game";
                            $query_games = mysqli_query($connect, $sql_games);

                            if ($query_games && mysqli_num_rows($query_games) > 0) {
                                while ($data_game = mysqli_fetch_assoc($query_games)) {
                            ?>
                                    <div class="col-lg-4 col-md-6 col-sm-6"> {/* Mengubah dari col-lg-3 menjadi col-lg-4 agar lebih pas 3 item per baris */}
                                        <div class="product__item">
                                            {/* Asumsi gambar masih ada di img/game/ atau Anda akan menggantinya dengan placeholder/URL dari DB */}
                                            <div class="product__item__pic set-bg" data-setbg="img/game/<?php echo htmlspecialchars($data_game['id_game']); ?>.jpg">
                                                <div class="ep">Harga : Rp.<?php echo number_format($data_game['harga']); ?></div>
                                            </div>
                                            <div class="product__item__text">
                                                <ul>
                                                    <li>ID game : <?php echo htmlspecialchars($data_game['id_game']); ?> </li>
                                                    <li>Developer : <?php echo htmlspecialchars($data_game['nama_dev']); ?></li>
                                                    <li>Genre : <?php echo htmlspecialchars($data_game['genre_1'] . ($data_game['genre_2'] ? ', ' . $data_game['genre_2'] : '') . ($data_game['genre_3'] ? ', ' . $data_game['genre_3'] : '')); ?></li>
                                                    <li>Specification : <?php echo htmlspecialchars($data_game['spek']); ?></li>
                                                    <li>Release Date : <?php echo htmlspecialchars(date('d M Y', strtotime($data_game['tanggal_rilis']))); // Format tanggal ?></li>
                                                </ul>
                                                <h5><a href="index.php?page=user_view_game&id_game=<?php echo urlencode($data_game['id_game']); ?>"><?php echo htmlspecialchars($data_game['nama_game']); ?></a></h5>
                                                <ul>
                                                    <li><a href="index.php?page=admin_edit_game&id_game=<?php echo urlencode($data_game['id_game']); ?>">Edit</a></li>
                                                    <li><a href="index.php?page=admin_hapus_game&id_game=<?php echo urlencode($data_game['id_game']); ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus game ini?');">Hapus</a></li> {/* Tambahkan konfirmasi hapus */}
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            } else {
                                if (!$query_games) {
                                    error_log("Admin Data Game: Gagal mengambil data game: " . mysqli_error($connect));
                                }
                                echo "<div class='col-12'><p>Tidak ada data game yang tersedia.</p></div>";
                            }
                            ?>
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
