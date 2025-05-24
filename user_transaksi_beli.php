<?php
session_start();
if (empty($_SESSION['username'])) {
    header("location:login.php?pesan=belum_login");
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
    <title>LGS Buy</title>
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
                    <div class="header__logo">
                        <a href="user_dashboard.php">
                            <img src="img/1.png" alt=""> <!-- Logo Toko--->
                        </a>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="header__nav">
                        <nav class="header__menu mobile-menu">
                            <ul>
                                <li><a href="user_dashboard.php">Homepage</a></li>
                                <li><a href="user_games.php">My Library </a>
                                </li>

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

                                        <li><a href="user_edit.php?">Edit Data</a></li>
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

    <!-- Hero Section Begin -->
    <section class="hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-8">
                    <div class="section-title">
                        <h4>Details</h4>
                    </div>
                </div>
            </div>
            <?php
            include 'koneksi.php';
            $id_game = $_GET['id_game'];

            $query = mysqli_query($connect, "SELECT * FROM game WHERE id_game=$id_game");
            $data = mysqli_fetch_array($query);
            ?>

            <div class="hero__slider owl-carousel">
                <div class="hero__items set-bg" data-setbg="img/buy/<?php echo $data['id_game']; ?>.jpg"><br><br>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="hero__text">

                                <form method="POST" action="user_transaksi_beli_cek.php">
                                    <input type="hidden" name="id_game" value="<?= $data['id_game']; ?>">
                                    <div class="label">Rp <input type="hidden" name="harga" value="<?= $data['harga']; ?>"> <?= $data['harga']; ?></div>
                                    <div style="background: black; opacity: 0.8; padding: 0px 0px 0px 10px; border-radius: 10px 10px 10px 10px;">

                                        <h2><input type="hidden" name="nama_game" value="<?= $data['nama_game']; ?>"> <?= $data['nama_game']; ?></h2>
                                        <p>
                                            <b>Developer</b> : <?= $data['nama_dev']; ?> <br>
                                            <b>Genre</b> : <?= $data['genre_1']; ?>, <?= $data['genre_2']; ?>, <?= $data['genre_3']; ?> <br>
                                            <b>Release</b> : <?= $data['tanggal_rilis']; ?><br>
                                            <b>Specification</b> : <?= $data['spek']; ?>
                                        </p>
                                    </div>
                                        <a><button class="btn" and style="background-color:transparent"><span>BUY NOw</span> <i class="fa fa-angle-right"></i></button></a>
                                        <br><br><br><br><br>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section><br><br><br><br><br><br><br>
    <!-- Hero Section End -->



    <!-- Footer Section Begin -->
    <footer class="footer">

        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="footer__logo">
                        <a href="user_dashboard.php"><img src="img/1.png" alt=""></a>
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
                        Copyright &copy;
                        <script>
                            document.write(new Date().getFullYear());
                        </script> 

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