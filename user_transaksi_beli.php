<?php
// Selalu mulai sesi di baris paling atas
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autentikasi User
if (!(isset($_SESSION['id_user']) && isset($_SESSION['username']))) { //
    header("location: index.php?page=login&pesan=belum_login");
    exit;
}

include 'koneksi.php'; //

$game_data = null;
$id_game = null;

if (isset($_GET['id_game']) && filter_var($_GET['id_game'], FILTER_VALIDATE_INT)) {
    $id_game = (int)$_GET['id_game']; //

    // Ambil data game menggunakan prepared statement
    $stmt = mysqli_prepare($connect, "SELECT id_game, nama_game, nama_dev, harga, genre_1, genre_2, genre_3, spek, tanggal_rilis FROM game WHERE id_game = ?"); //
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id_game);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $game_data = mysqli_fetch_assoc($result); //
        mysqli_stmt_close($stmt);
    } else {
        error_log("User Transaksi Beli: Gagal prepare statement: " . mysqli_error($connect));
    }
}

if (!$game_data) {
    // Jika game tidak ditemukan atau ID tidak valid, redirect atau tampilkan pesan error
    $_SESSION['pesan_error'] = "Game tidak ditemukan.";
    header("Location: index.php?page=user_dashboard"); // Kembali ke dashboard user
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
    <title>Beli <?php echo htmlspecialchars($game_data['nama_game']); ?> - LGS</title> 
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
                        <a href="index.php?page=user_dashboard">
                            <img src="img/1.png" alt="Logo Toko">
                        </a>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="header__nav">
                        <nav class="header__menu mobile-menu">
                            <ul>
                                <li><a href="index.php?page=user_dashboard">Homepage</a></li>
                                <li><a href="index.php?page=user_games">My Library </a></li>
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
    <section class="hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-8">
                    <div class="section-title">
                        <h4>Detail Pembelian</h4> 
                    </div>
                </div>
            </div>
            <?php
            // Menampilkan pesan error dari session jika ada (misalnya dari proses transaksi)
            if (isset($_SESSION['pesan_error_transaksi'])) {
                echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>" . htmlspecialchars($_SESSION['pesan_error_transaksi']) . "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
                unset($_SESSION['pesan_error_transaksi']);
            }
            ?>
            <div class="hero__slider owl-carousel">
               
                <div class="hero__items set-bg" data-setbg="img/buy/<?php echo htmlspecialchars($game_data['id_game']); ?>.jpg"><br><br>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="hero__text">
                               
                                <form method="POST" action="index.php?page=user_transaksi_beli_cek">
                                    <input type="hidden" name="id_game" value="<?php echo htmlspecialchars($game_data['id_game']); ?>">
                                   
                                    <div class="label">Rp <?php echo number_format($game_data['harga']); // XSS Prevention & Formatting ?></div>
                                    <div style="background: black; opacity: 0.8; padding: 10px; border-radius: 10px;">
                                        <h2><?php echo htmlspecialchars($game_data['nama_game']); // XSS Prevention ?></h2>
                                        <p>
                                            <b>Developer</b> : <?php echo htmlspecialchars($game_data['nama_dev']); ?> <br>
                                            <b>Genre</b> : <?php echo htmlspecialchars($game_data['genre_1'] . ($game_data['genre_2'] ? ', ' . $game_data['genre_2'] : '') . ($game_data['genre_3'] ? ', ' . $game_data['genre_3'] : '')); ?> <br>
                                            <b>Release</b> : <?php echo htmlspecialchars(date('d M Y', strtotime($game_data['tanggal_rilis']))); // Format tanggal ?><br>
                                            <b>Specification</b> : <?php echo htmlspecialchars($game_data['spek']); ?>
                                        </p>
                                    </div>
                                  
                                    <a><button class="btn" and style="background-color:transparent"><span>BUY NOw</span> <i class="fa fa-angle-right"></i></button></a>
                                        <br><br><br><br><br>
                                </form>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><br><br><br><br><br><br><br>
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="footer__logo">
                        <a href="index.php?page=user_dashboard"><img src="img/1.png" alt="Logo Footer"></a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="footer__nav">
                        <ul></ul>
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
