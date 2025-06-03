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
$user_data = null;
$id_game_from_post = null;

// Pastikan ini adalah request POST dan id_game ada
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_game'])) {
    $id_game_from_post = filter_var($_POST['id_game'], FILTER_VALIDATE_INT); //

    if ($id_game_from_post === false || $id_game_from_post <= 0) {
        $_SESSION['pesan_error_transaksi'] = "ID Game tidak valid.";
        header("Location: index.php?page=user_dashboard");
        exit;
    }

    // Ambil data game dari database berdasarkan id_game dari POST (untuk konfirmasi)
    $stmt_game = mysqli_prepare($connect, "SELECT id_game, nama_game, harga FROM game WHERE id_game = ?"); //
    if ($stmt_game) {
        mysqli_stmt_bind_param($stmt_game, "i", $id_game_from_post);
        mysqli_stmt_execute($stmt_game);
        $result_game = mysqli_stmt_get_result($stmt_game);
        $game_data = mysqli_fetch_assoc($result_game); //
        mysqli_stmt_close($stmt_game);
    } else {
        error_log("User Transaksi Cek: Gagal prepare statement game: " . mysqli_error($connect));
    }

    if (!$game_data) {
        $_SESSION['pesan_error_transaksi'] = "Game yang ingin dibeli tidak ditemukan.";
        header("Location: index.php?page=user_dashboard");
        exit;
    }

    // Ambil data user dari database berdasarkan username session
    $username_session = $_SESSION['username']; //
    $stmt_user = mysqli_prepare($connect, "SELECT email, no_telp FROM user WHERE username = ?"); //
    if ($stmt_user) {
        mysqli_stmt_bind_param($stmt_user, "s", $username_session);
        mysqli_stmt_execute($stmt_user);
        $result_user = mysqli_stmt_get_result($stmt_user);
        // Tidak perlu loop karena username unik, cukup fetch satu baris
        $user_data = mysqli_fetch_assoc($result_user); //
        mysqli_stmt_close($stmt_user);
    } else {
        error_log("User Transaksi Cek: Gagal prepare statement user: " . mysqli_error($connect));
    }

    if (!$user_data) {
        $_SESSION['pesan_error'] = "Data user tidak ditemukan. Silakan login kembali.";
        header("Location: index.php?page=logout"); // Logout jika data user session tidak valid
        exit;
    }

} else {
    // Jika bukan POST atau id_game tidak ada, redirect
    $_SESSION['pesan_error'] = "Akses tidak sah ke halaman konfirmasi.";
    header("Location: index.php?page=user_dashboard");
    exit;
}

$email_user = $user_data['email']; //
$telp_user = $user_data['no_telp']; //
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Anime Template">
    <meta name="keywords" content="Anime, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Konfirmasi Pembelian - LGS</title>
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
                        <h4>Konfirmasi Pembelian</h4>
                    </div>
                </div>
            </div>
            <?php
            // Menampilkan pesan error dari session jika ada
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
                              
                                <form method="POST" action="index.php?page=user_proses_transaksi_beli">
                                  
                                    <input type="hidden" name="id_game" value="<?php echo htmlspecialchars($game_data['id_game']); ?>">
                                    <input type="hidden" name="nama_game" value="<?php echo htmlspecialchars($game_data['nama_game']); ?>">
                                    <input type="hidden" name="harga" value="<?php echo htmlspecialchars($game_data['harga']); ?>">

                                    <div class="label">Rp <?php echo number_format($game_data['harga']); // XSS Prevention & Formatting ?></div>
                                    <div style="background: black; opacity: 0.8; padding: 10px; border-radius: 10px;">
                                        <h2><?php echo htmlspecialchars($game_data['nama_game']); // XSS Prevention ?></h2>
                                        <p>
                                            <b>Username</b> : <?php echo htmlspecialchars($_SESSION['username']); ?><br>
                                            <b>Email</b> : <?php echo htmlspecialchars($email_user); ?><br>
                                            <b>No Telp</b> : <?php echo htmlspecialchars($telp_user); ?><br><br>
                                            Tagihan akan dikirim lewat email dan no telp, bisa dibayar lewat (Dana, Paypal, DLL). Jika tagihan sudah dibayar maka Game akan otomatis masuk ke Library.
                                        </p>
                                    </div>
                                    <a><button class="btn" and style="background-color:transparent"><span>BAYAR</span> <i class="fa fa-angle-right"></i></button></a>
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
