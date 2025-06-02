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
    <title>Edit Data User</title>
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
                                <li class="active"><a href="user_dashboard.php">Homepage</a></li>
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

    <!-- Normal Breadcrumb Begin -->
    <section class="normal-breadcrumb set-bg" data-setbg="img/normal-breadcrumb.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="normal__breadcrumb__text">
                        <h2>Edit data user</h2>
                        <p><?php echo $_SESSION['username'] ?></p>
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
                        $id_user = $_SESSION['id_user'];

                        $query = mysqli_query($connect, "SELECT * FROM user WHERE id_user=$id_user");
                        $data = mysqli_fetch_array($query);
                        ?>
                            <form method="POST" action="index.php?page=user_edit_proses">
                                <input type="hidden" name="id_user" value="<?= $id_user?>">
                                <div class="input__item">
                                    <input type="text" name="username" value="<?= $data['username']; ?>" readonly>
                                    <span class="icon_lock"></span>
                                </div>
                                <div class="input__item">
                                    <input type="text" name="password" value="<?= $data['password']; ?>" readonly>
                                    <span class="icon_lock"></span>
                                </div>
                                <div class="input__item">
                                    <input type="text" name="email" value="<?= $data['email']; ?>">
                                    <span class="icon_mail"></span>
                                </div>
                                <div class="input__item">
                                    <input type="number" name="no_telp" value="<?= $data['no_telp']; ?>">
                                    <span class="icon_mail"></span>
                                </div>
                        <button type="submit" class="site-btn">Update</button>
                        </form>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="login__register">
                        <h3>Cancel?</h3>
                        <a href="user_dashboard.php" class="primary-btn">Back</a>
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
