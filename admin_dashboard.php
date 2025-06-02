<?php
session_start();
// Pengecekan sesi admin
if (!(isset($_SESSION['username']) && isset($_SESSION['jenis_login']) && $_SESSION['jenis_login'] == 'admin')) {
    header("location: index.php?page=admin_login&pesan=belum_login_admin");
    exit;
}
include 'koneksi.php'; // Untuk query total penghasilan
?>

<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Anime Template">
    <meta name="keywords" content="Anime, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LGS Dashboard Admin - <?php echo htmlspecialchars($_SESSION['username']); ?></title>
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
    <div id="preloder"><div class="loader"></div></div>
    <header class="header">
        <div class="container">
            <div class="row">
                <div class="col-lg-2">
                    <div class="header__logo">
                        <a href="index.php?page=admin_dashboard">
                            <img src="img/1.png" alt="LGS Logo">
                        </a>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="header__nav">
                        <nav class="header__menu mobile-menu">
                            <ul>
                                <li class="active"><a href="index.php?page=admin_dashboard">Homepage</a></li>
                                <li><a href="index.php?page=admin_data_game">Games </a></li>
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
                                <li><a href="#">Hallo <?php echo htmlspecialchars($_SESSION['username']); ?> <span class="arrow_carrot-down"></span></a>
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

    <section class="hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-8">
                    <div class="section-title"><h4>Welcome Admin</h4></div>
                </div>
            </div>
            <div class="hero__slider owl-carousel">
                <div class="hero__items set-bg" data-setbg="img/head/admin.jpg">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="hero__text" style="background: rgba(0,0,0,0.7); padding: 20px; border-radius: 10px;">
                                <h2>Penghasilan</h2>
                                <p><?php
                                    $total_penghasilan = 0;
                                    $sql_total = "SELECT SUM(harga) AS total FROM transaksi";
                                    $query_total = mysqli_query($connect, $sql_total);
                                    if ($query_total && mysqli_num_rows($query_total) > 0) {
                                        $data_total = mysqli_fetch_assoc($query_total);
                                        $total_penghasilan = $data_total['total'] ?: 0;
                                    }
                                    echo "Rp. " . htmlspecialchars(number_format($total_penghasilan, 0, ',', '.'));
                                    $sql_update_admin = "UPDATE `admin` SET `total_penghasilan` = '$total_penghasilan' WHERE username = '" . mysqli_real_escape_string($connect, $_SESSION['username']) . "'";
                                    mysqli_query($connect, $sql_update_admin);
                                ?></p>
                                <br><br><br><br><br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-10"> <div class="trending__product">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="section-title"><h4>All Games (Contoh)</h4></div>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            $sql_games    = "SELECT id_game, nama_game, harga, genre_1, genre_2, genre_3 FROM game ORDER BY id_game DESC LIMIT 6"; // Ambil beberapa game
                            $query_games    = mysqli_query($connect, $sql_games);
                            $bucketNameEnv = getenv('GCS_BUCKET_NAME') ?: 'ta-tcc';

                            if($query_games){
                                while ($data = mysqli_fetch_array($query_games)) {
                                    // Kita asumsikan ekstensi .jpg, atau Anda bisa coba beberapa ekstensi
                                    $imagePath = "https://storage.googleapis.com/{$bucketNameEnv}/img/game/" . rawurlencode($data['id_game']) . ".jpg";
                                    // Untuk memastikan gambar ada, idealnya Anda cek dulu atau punya mekanisme fallback
                            ?>
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="product__item">
                                        <div class="product__item__pic set-bg" data-setbg="<?php echo htmlspecialchars($imagePath); ?>">
                                            <div class="ep">Rp. <?php echo htmlspecialchars(number_format($data['harga'], 0, ',', '.')); ?></div>
                                        </div>
                                        <div class="product__item__text">
                                            <ul>
                                                <li><?php echo htmlspecialchars($data['genre_1']); ?></li>
                                                <?php if(!empty($data['genre_2'])): ?><li><?php echo htmlspecialchars($data['genre_2']); ?></li><?php endif; ?>
                                                <?php if(!empty($data['genre_3'])): ?><li><?php echo htmlspecialchars($data['genre_3']); ?></li><?php endif; ?>
                                            </ul>
                                            <h5><a href="index.php?page=admin_edit_game&id_game=<?php echo $data['id_game']; ?>"><?php echo htmlspecialchars($data['nama_game']); ?></a></h5>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer"></footer>
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
