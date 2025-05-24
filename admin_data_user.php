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
    <title>User LGS</title>
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
                        <a href="admin_dashboard.php">
                            <img src="img/1.png" alt=""> <!-- Logo Toko--->
                        </a>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="header__nav">
                        <nav class="header__menu mobile-menu">
                            <ul>
                                <li><a href="admin_dashboard.php">Homepage</a></li>
                                <li><a href="admin_data_game.php">Games </a></li>
                                <li><a href="admin_data_transaksi.php">Transaksi</a></li>
                                <li class="active"><a href="admin_data_user.php">User</a></li>
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

    <!-- Product Section Begin -->
    <section class="product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="trending__product">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="section-title">
                                    <h4>Data User</h4><br><br>
                                    <a href="admin_tambah_user.php?" class="primary-btn" ><b>--> Tambah USer</b></a>
                                </div>
                            </div>
                        </div>


                        <table class="table table-secondary table-striped">
                            <thead>
                                <tr>
                                    <th>Id User</th>
                                    <th>Username</th>
                                    <th>Password</th>
                                    <th>Email</th>
                                    <th>No Telp</th>
                                    <th>Tanggal Buat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <?php
                            include('koneksi.php');

                            $sql    = "SELECT * FROM user";
                            $query    = mysqli_query($connect, $sql);

                            while ($data = mysqli_fetch_array($query)) {
                            ?>
                                <tr>
                                    <th><?= $data['id_user']; ?></th>
                                    <td><?= $data['username']; ?></td>
                                    <td><?= $data['password']; ?></td>
                                    <td><?= $data['email']; ?></td>
                                    <td><?= $data['no_telp']; ?></td>
                                    <td><?= $data['tanggal_dibuat']; ?></td>
                                    <td>
                                        <a href="admin_hapus_user.php?id_user=<?php echo $data['id_user']; ?>">Hapus</a>
                                    </td>
                                </tr>
                            <?php } ?>

                        </table>

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
                        <a href="admin_dashboard.php"><img src="img/1.png" alt=""></a>
                    </div>
                </div>
                <div class="col-lg-6">

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