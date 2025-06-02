<?php
session_start();
if (!(isset($_SESSION['id_user']) && isset($_SESSION['username']))) { // Pastikan konsisten dengan index.php
    header("location: index.php?page=login&pesan=belum_login");
    exit;
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
    <title>LGS Home</title>
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
                                <li class="active"><a href="index.php?page=user_dashboard">Homepage</a></li>
                                <li><a href="index.php?page=user_game">My Library </a>                                   
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
    <!-- Header End -->

    <!-- Hero Section Begin -->
    <section class="hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-8">
                    <div class="section-title">
                        <h4>Rekomendasi</h4>
                    </div>
                </div>
            </div>
            <div class="hero__slider owl-carousel">
                <div class="hero__items set-bg" data-setbg="img/head/32.png">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="hero__text">
                                <div class="label">Open World</div>
                                <h2>Elden Ring</h2>
                                <p>THE NEW FANTASY ACTION RPG. Rise, Tarnished, and be guided by grace to brandish the
                                    power of the Elden Ring and become an Elden Lord in the Lands Between.</p>
                                <a href="index.php?page=user_transaksi_beli?id_game=32"><span>Buy Now</span> <i class="fa fa-angle-right"></i></a>
                                <br><br><br><br><br>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hero__items set-bg" data-setbg="img/head/1.jpg">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="hero__text">
                                <div class="label">FPS</div>
                                <h2>Call of Duty®: Modern Warfare® II</h2>
                                <p>Call of Duty®: Modern Warfare® II drops players into an unprecedented global conflict
                                    that features the return of the iconic Operators of Task Force 141.</p>
                                <a href="index.php?page=user_transaksi_beli?id_game=26"><span>Buy Now</span> <i class="fa fa-angle-right"></i></a>
                                <br><br><br>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hero__items set-bg" data-setbg="img/head/33.jpg">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="hero__text">
                                <div class="label">Open World</div>
                                <h2>The Elder Scrolls V: Skyrim Special Edition</h2>
                                <p>Winner of more than 200 Game of the Year Awards, Skyrim Special Edition brings the
                                    epic fantasy to life in stunning detail. The Special Edition includes the critically
                                    acclaimed game and add-ons with all-new features like remastered art and effects,
                                    volumetric god rays, dynamic depth of field, screen-space...</p>
                                <a href="index.php?page=user_transaksi_beli?id_game=33"><span>Buy Now</span> <i class="fa fa-angle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- Product Section Begin -->
    <section class="product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="trending__product">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="section-title">
                                    <h4>All GAmes</h4>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        <?php
			include('koneksi.php');

			$sql	= "SELECT * FROM game";
			$query	= mysqli_query($connect, $sql);

			while ($data = mysqli_fetch_array($query)) {
			?>
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="product__item">
                                    <div class="product__item__pic set-bg" data-setbg="img/game/<?php echo $data['id_game'];?>.jpg">
                                        <div class="ep">Rp. <?= $data['harga']; ?></div> <!-- rating game -->
                                    </div>
                                    <div class="product__item__text">
                                        <ul>
                                            <li><?= $data['genre_1']; ?></li>
                                            <li><?= $data['genre_2']; ?></li>
                                            <li><?= $data['genre_3']; ?></li>
                                        </ul>
                                        <h5><a href="index.php?page=user_transaksi_beli?id_game=<?php echo $data['id_game'];?>"><?= $data['nama_game']; ?></a></h5>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
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
                                <div class="product__sidebar__view__item set-bg" data-setbg="img/dragon.jpg">
                                    <div class="ep">8,2 / 10 Rating</div>
                                    <h5><a href="index.php?page=user_transaksi_beli?id_game=27">Dragon Age: Inquisition</a></h5>
                                </div>
                                <div class="filter__gallery">
                                    <div class="product__sidebar__view__item set-bg" data-setbg="img/witcher.jpg">
                                        <div class="ep">8,3 / 10 Rating</div>
                                        <h5><a href="index.php?page=user_transaksi_beli?id_game=28">The Witcher 3: Wild Hunt</a></h5>
                                    </div>
                                    <div class="filter__gallery">
                                        <div class="product__sidebar__view__item set-bg" data-setbg="img/ragnarok.jpg">
                                            <div class="ep">8,1 / 10 Rating</div>
                                            <h5><a href="index.php?page=user_transaksi_beli?id_game=29">God of War</a></h5>
                                        </div>
                                        <div class="filter__gallery">
                                            <div class="product__sidebar__view__item set-bg" data-setbg="img/sekiro.jpg">
                                                <div class="ep">8 / 10 Rating</div>
                                                <h5><a href="index.php?page=user_transaksi_beli?id_game=1">Sekiro: Shadows Die Twice</a></h5>
                                            </div>
                                            <div class="filter__gallery">
                                                <div class="product__sidebar__view__item set-bg" data-setbg="img/buy/31.jpg">
                                                    <div class="ep">8,6 / 10 Rating</div>
                                                    <h5><a href="index.php?page=user_transaksi_beli?id_game=31">It Takes Two</a></h5>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
    </section>
    <!-- Product Section End -->

    <!-- Footer Section Begin -->
    <footer class="footer">
        <div class="page-up">
            <a href="#" id="scrollToTopButton"><span class="arrow_carrot-up"></span></a>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="footer__logo">
                        <a href="./index.html"><img src="img/1.png" alt=""></a>
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
