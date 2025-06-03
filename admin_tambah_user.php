<?php
// Selalu mulai sesi di baris paling atas
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autentikasi Admin
if (!isset($_SESSION['username']) || !isset($_SESSION['jenis_login']) || $_SESSION['jenis_login'] != 'admin') { //
    header("location: index.php?page=admin_login&pesan=belum_login"); // Perbaikan Route
    exit;
}
// Tidak perlu include koneksi.php karena file ini hanya menampilkan form
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Anime Template">
    <meta name="keywords" content="Anime, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tambah Akun User - LGS Admin</title> {/* Judul diubah */}
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
                            <img src="img/1.png" alt="Logo">
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
    <section class="normal-breadcrumb set-bg" data-setbg="img/normal-breadcrumb.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="normal__breadcrumb__text">
                        <h2>Administrator</h2>
                        <p>Tambah User Baru</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="login spad">
        <div class="container">
            <div class="row justify-content-center"> {/* Memusatkan form */}
                <div class="col-lg-7"> {/* Sedikit diperlebar untuk form */}
                    <div class="login__form">
                        <h3>Tambah Akun User</h3><br>
                        <?php
                        // Menampilkan pesan error dari session jika ada (misalnya dari proses tambah user)
                        if (isset($_SESSION['pesan_error_tambah_user'])) {
                            echo "<p style='color: red;'>" . htmlspecialchars($_SESSION['pesan_error_tambah_user']) . "</p>";
                            unset($_SESSION['pesan_error_tambah_user']); // Hapus pesan setelah ditampilkan
                        }
                        ?>
                        {/* Perbaikan Route: Action form mengarah ke index.php */}
                        <form method="POST" action="index.php?page=admin_tambah_user_proses">
                            <div class="input__item">
                                <input type="text" name="username" required placeholder="Username">
                                <span class="icon_profile"></span> {/* Icon diganti agar lebih sesuai */}
                            </div>
                            <div class="input__item">
                                <input type="email" name="email" required placeholder="Email"> {/* Typo 'Email' diperbaiki */}
                                <span class="icon_mail"></span>
                            </div>
                            <div class="input__item">
                                <input type="text" name="no_telp" required placeholder="No Telp">
                                <span class="icon_phone"></span> {/* Icon diganti agar lebih sesuai */}
                            </div>
                            <div class="input__item">
                                <input type="password" name="password" required placeholder="Password">
                                <span class="icon_lock"></span>
                            </div>
                            <button type="submit" class="site-btn">Tambahkan User</button>
                        </form>
                        <div class="text-center mt-3">
                             {/* Perbaikan Route: Link kembali */}
                            <a href="index.php?page=admin_data_user" class="primary-btn" style="background-color: #6c757d; border-color: #6c757d;">Kembali ke Data User</a>
                        </div>
                    </div>
                </div>
                {/* Kolom register tidak relevan di sini, jadi bisa dihapus atau dikosongkan */}
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
                    <div class="footer__nav">
                        <ul>
                            {/* Isi dengan link footer yang relevan jika ada */}
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3">
                    <p>
                        Copyright &copy;<script>document.write(new Date().getFullYear());</script>
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
