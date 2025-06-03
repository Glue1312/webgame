<?php
// Selalu mulai sesi di baris paling atas sebelum output apapun
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autentikasi User (lapisan tambahan jika file diakses langsung)
// Pastikan konsisten dengan index.php
if (!(isset($_SESSION['id_user']) && isset($_SESSION['username']))) {
    header("location: index.php?page=login&pesan=belum_login");
    exit;
}

include 'koneksi.php'; //
$id_user = $_SESSION['id_user']; //
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Anime Template">
    <meta name="keywords" content="Anime, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My Library - LGS</title> {/* Judul disesuaikan */}
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
                        {/* Perbaikan Route */}
                        <a href="index.php?page=user_dashboard">
                            <img src="img/1.png" alt="Logo Toko">
                        </a>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="header__nav">
                        <nav class="header__menu mobile-menu">
                            <ul>
                                {/* Perbaikan Route */}
                                <li><a href="index.php?page=user_dashboard">Homepage</a></li>
                                <li class="active"><a href="index.php?page=user_games">My Library</a></li>
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
                                        {/* Perbaikan Route */}
                                        <li><a href="index.php?page=user_edit">Edit Data</a></li>
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
                <div class="col-lg-8">
                    <div class="trending__product">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="section-title">
                                    <h4>My Library</h4>
                                </div>
                            </div>
                        </div>
                        <?php
                        // Menampilkan pesan sukses dari session jika ada (misalnya setelah pembelian)
                        if (isset($_SESSION['pesan_sukses'])) {
                            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>" . htmlspecialchars($_SESSION['pesan_sukses']) . "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
                            unset($_SESSION['pesan_sukses']);
                        }
                        ?>
                        <div class="row">
                        <?php
                        // Menggunakan prepared statement untuk mengambil data transaksi pengguna
                        $query_transaksi_user = null;
                        $stmt = mysqli_prepare($connect, "SELECT id_game, nama_game, `key`, tanggal_transaksi FROM transaksi WHERE id_user = ? ORDER BY tanggal_transaksi DESC");
                        if ($stmt) {
                            mysqli_stmt_bind_param($stmt, "i", $id_user);
                            mysqli_stmt_execute($stmt);
                            $query_transaksi_user = mysqli_stmt_get_result($stmt);
                        } else {
                            error_log("User Games: Gagal prepare statement: " . mysqli_error($connect));
                        }

                        if ($query_transaksi_user && mysqli_num_rows($query_transaksi_user) > 0) {
                            while ($data = mysqli_fetch_assoc($query_transaksi_user)) {
                        ?>
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="product__item">
                                    {/* Asumsi gambar game masih ada, jika tidak perlu disesuaikan */}
                                    <div class="product__item__pic set-bg" data-setbg="img/game/<?php echo htmlspecialchars($data['id_game']);?>.jpg">
                                        {/* Rating bisa dinamis jika ada datanya */}
                                        <div class="ep">10 / 10 Rating</div>
                                    </div>
                                    <div class="product__item__text">
                                        <ul>
                                            <li>Kode Key</li>
                                            <li><?php echo htmlspecialchars($data['key']); // XSS Prevention ?></li>
                                            <li>Tanggal Transaksi</li>
                                            <li><?php echo htmlspecialchars(date('d M Y, H:i:s', strtotime($data['tanggal_transaksi']))); // Format tanggal ?></li>
                                        </ul>
                                        {/* Perbaikan Route & XSS Prevention & URL Encoding */}
                                        <h5><a href="index.php?page=user_view_game&id_game=<?php echo urlencode($data['id_game']);?>"><?php echo htmlspecialchars($data['nama_game']); ?></a></h5>
                                    </div>
                                </div>
                            </div>
                        <?php
                            } // End while
                            if ($stmt) mysqli_stmt_close($stmt);
                        } else {
                            if (!$query_transaksi_user && $stmt) error_log("User Games: Gagal execute atau get result: " . mysqli_stmt_error($stmt));
                            echo "<div class='col-12'><p>Belum ada game di library Anda.</p></div>";
                        }
                        ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 col-sm-8">
                    <div class="product__sidebar">
                        <div class="product__sidebar__view">
                            <div class="section-title">
                                <h4>Trending</h4>
                            </div>
                            <div class="filter__gallery">
                                {/* Contoh game trending, pastikan link menggunakan routing yang benar dan data di-escape jika dinamis */}
                                <div class="product__sidebar__view__item set-bg" data-setbg="img/dragon.jpg">
                                    <div class="ep">8,2 / 10 Rating</div>
                                    <h5><a href="index.php?page=user_transaksi_beli&id_game=27">Dragon Age: Inquisition</a></h5>
                                </div>
                                <div class="product__sidebar__view__item set-bg" data-setbg="img/witcher.jpg">
                                    <div class="ep">8,3 / 10 Rating</div>
                                    <h5><a href="index.php?page=user_transaksi_beli&id_game=28">The Witcher 3: Wild Hunt</a></h5>
                                </div>
                                <div class="product__sidebar__view__item set-bg" data-setbg="img/ragnarok.jpg">
                                    <div class="ep">8,1 / 10 Rating</div>
                                    <h5><a href="index.php?page=user_transaksi_beli&id_game=29">God of War</a></h5>
                                </div>
                                <div class="product__sidebar__view__item set-bg" data-setbg="img/sekiro.jpg">
                                    <div class="ep">8 / 10 Rating</div>
                                    <h5><a href="index.php?page=user_transaksi_beli&id_game=1">Sekiro: Shadows Die Twice</a></h5>
                                </div>
                                <div class="product__sidebar__view__item set-bg" data-setbg="img/buy/31.jpg">
                                    <div class="ep">8,6 / 10 Rating</div>
                                    <h5><a href="index.php?page=user_transaksi_beli&id_game=31">It Takes Two</a></h5>
                                </div>
                            </div>
                        </div>
                        {/* Anda bisa menambahkan bagian lain di sidebar jika ada */}
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
                        <a href="index.php?page=user_dashboard"><img src="img/1.png" alt="Logo Footer"></a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="footer__nav">
                        <ul>
                            {/* Isi dengan link footer yang relevan jika ada */}
                        </ul>
                    </div>
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
