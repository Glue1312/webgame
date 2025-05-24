<?php
session_start();
if ($_SESSION['jenis_login'] != 'admin') {
    header("location:login.php?pesan=belum_login_admin");
} else if (empty($_SESSION['username'])) {
    header("location:admin_login.php?pesan=belum_login");
}
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Anime Template">
    <meta name="keywords" content="Anime, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Game LGS</title>
    <link rel="shortcut icon" href="img/1.png">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Css Styles -->
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
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Header Section Begin -->
    <header class="header">
        <div class="container">
            <div class="row">
                <div class="col-lg-2">
                    <div class="header__nav">
                        <nav class="header__menu mobile-menu">
                            <ul>
                                <li><a>LGS</a></li>

                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="header__nav">
                        <nav class="header__menu mobile-menu">
                            <ul>
                                <li><a href="admin_dashboard.php">Homepage</a></li>
                                <li  class="active"><a href="admin_data_game.php">Games </a></li>
                                <li><a href="admin_data_transaksi.php">Transaksi</a></li>
                                <li><a href="admin_data_user.php">User</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="header__nav ms-auto">
                        <nav class="header__menu mobile-menu">
                            <ul>
                                <li><a href="#">Hallo <?php echo $_SESSION['username'] ?> <span class="arrow_carrot-down"></span></a>
                                    <ul class="dropdown">                                        
                                        <li><a href="logout.php?">Logout</a></li>
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
    <!-- Header End -->

    <!-- Normal Breadcrumb Begin -->
    <section class="normal-breadcrumb set-bg" data-setbg="img/normal-breadcrumb.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="normal__breadcrumb__text">
                        <h2>Edit data game</h2>
                        <p>Administrator</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Normal Breadcrumb End -->

    <!-- Login Section Begin -->
    <section class="login spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <div class="login__form">
                        <h3>Edit</h3><br>
                        
                        <?php
                        include 'koneksi.php';
                        $id_game = $_GET['id_game'];

                        $query = mysqli_query($connect, "SELECT * FROM game WHERE id_game=$id_game");
                        $data = mysqli_fetch_array($query);
                        ?>
                        
                            <form method="POST" action="admin_edit_game_proses.php" enctype="multipart/form-data">
                                <div class="input__item">
                                    <input type="text" name="id_game" value="<?= $data['id_game']; ?>" readonly>
                                    <span class="icon_lock"></span>
                                </div>
                                <div class="input__item">
                                    <input type="text" name="nama_game" value="<?= $data['nama_game']; ?>">
                                    <span class="icon_mail"></span>
                                </div>
                                <div class="input__item">
                                    <input type="text" name="nama_dev" value="<?= $data['nama_dev']; ?>"></td>
                                    <span class="icon_mail"></span>
                                </div>
                                <div class="input__item">
                                    <input type="number" name="harga" value="<?= $data['harga']; ?>"></td>
                                    <span class="icon_mail"></span>
                                </div>
                                <div class="input__item">
                                    <input type="text" name="genre_1" value="<?= $data['genre_1']; ?>">
                                    <span class="icon_mail"></span>
                                </div>
                                <div class="input__item">
                                    <input type="text" name="genre_2" value="<?= $data['genre_2']; ?>">
                                    <span class="icon_mail"></span>
                                </div>
                                <div class="input__item">
                                    <input type="text" name="genre_3" value="<?= $data['genre_3']; ?>">
                                    <span class="icon_mail"></span>
                                </div>
                                <div class="input__item">
                                    <input type="text" name="spek" value="<?= $data['spek']; ?>">
                                    <span class="icon_mail"></span>
                                </div>
                                <div class="input__item">
                                <input type="date" name="tanggal_rilis" value="<?=$data['tanggal_rilis'];?>">
                                    <span class="icon_mail"></span>
                                </div>
                                <div class="input__item">
                                <input class="form-control" type="file" name="fileToUpload">
                                    <span class="icon_mail"></span>
                                </div>
                        
                        <button type="submit" class="site-btn" value="Upload Image" name="submit">Update</button>
                        </form>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="login__register">
                        <h3>Cancel?</h3>
                        <a href="admin_data_game.php" class="primary-btn">Back</a>
                    </div>
                </div>

            </div>

        </div>
    </section>
    <!-- Login Section End -->

    <!-- Footer Section Begin -->
    <footer class="footer">
        <div class="page-up">
            <a href="#" id="scrollToTopButton"><span class="arrow_carrot-up"></span></a>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="footer__logo">
                        <a href="./index.html"><img src="img/logo.png" alt=""></a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="footer__nav">
                        <ul>

                        </ul>
                    </div>
                </div>
                <div class="col-lg-3">
                    <p>
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        Copyright &copy;<script>
                            document.write(new Date().getFullYear());
                        </script> 
                    </p>

                </div>
            </div>
        </div>
    </footer>
    <!-- Footer Section End -->

    <!-- Search model Begin -->
    <div class="search-model">
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="search-close-switch"><i class="icon_close"></i></div>
            <form class="search-model-form">
                <input type="text" id="search-input" placeholder="Search here.....">
            </form>
        </div>
    </div>
    <!-- Search model end -->

    <!-- Js Plugins -->
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