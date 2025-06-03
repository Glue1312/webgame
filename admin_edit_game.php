<?php
// Selalu mulai sesi di baris paling atas
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autentikasi Admin
if (!isset($_SESSION['username']) || !isset($_SESSION['jenis_login']) || $_SESSION['jenis_login'] != 'admin') { //
    header("location: index.php?page=admin_login&pesan=belum_login");
    exit;
}

include 'koneksi.php'; //

$id_game_to_edit = null;
$game_data = null;

if (isset($_GET['id_game']) && filter_var($_GET['id_game'], FILTER_VALIDATE_INT)) {
    $id_game_to_edit = (int)$_GET['id_game']; //

    // Ambil data game yang akan diedit menggunakan prepared statement
    $stmt = mysqli_prepare($connect, "SELECT * FROM game WHERE id_game = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id_game_to_edit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $game_data = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    } else {
        error_log("Admin Edit Game: Gagal prepare statement untuk mengambil data game: " . mysqli_error($connect));
        $_SESSION['pesan_error'] = "Gagal mengambil data game untuk diedit.";
        // Redirect atau tampilkan pesan error, tergantung kebutuhan
    }

    if (!$game_data) {
        $_SESSION['pesan_error'] = "Game dengan ID tersebut tidak ditemukan.";
        header("Location: index.php?page=admin_data_game");
        exit;
    }
} else {
    $_SESSION['pesan_error'] = "ID Game tidak valid untuk diedit.";
    header("Location: index.php?page=admin_data_game");
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
    <title>Edit Game - LGS Admin</title> {/* Judul diubah */}
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
                                <li><a href="index.php?page=admin_dashboard">Homepage</a></li>
                                <li class="active"><a href="index.php?page=admin_data_game">Games </a></li>
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
                        <h2>Edit Data Game</h2>
                        <p>Administrator</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="login spad">
        <div class="container">
            <div class="row justify-content-center"> 
                <div class="col-lg-8"> 
                    <div class="login__form">
                        <h3>Edit Game: <?php echo htmlspecialchars($game_data['nama_game']); ?></h3><br>
                        <?php
                        // Menampilkan pesan error dari session jika ada (misalnya dari proses edit)
                        if (isset($_SESSION['pesan_error_edit_game'])) {
                            echo "<p style='color: red;'>" . htmlspecialchars($_SESSION['pesan_error_edit_game']) . "</p>";
                            unset($_SESSION['pesan_error_edit_game']); // Hapus pesan setelah ditampilkan
                        }
                        ?>
                     
                        <form method="POST" action="index.php?page=admin_edit_game_proses">
                         
                            <input type="hidden" name="id_game" value="<?php echo htmlspecialchars($game_data['id_game']); ?>">
                            
                            <div class="input__item">
                                <input type="text" name="nama_game" value="<?php echo htmlspecialchars($game_data['nama_game']); ?>" required placeholder="Nama Game">
                                <span class="icon_tag"></span>
                            </div>
                            <div class="input__item">
                                <input type="text" name="nama_dev" value="<?php echo htmlspecialchars($game_data['nama_dev']); ?>" required placeholder="Developer">
                                <span class="icon_group"></span>
                            </div>
                            <div class="input__item">
                                <input type="number" name="harga" value="<?php echo htmlspecialchars($game_data['harga']); ?>" required placeholder="Harga" min="0">
                                <span class="icon_wallet"></span>
                            </div>
                            <div class="input__item">
                                <input type="text" name="genre_1" value="<?php echo htmlspecialchars($game_data['genre_1']); ?>" required placeholder="Genre 1">
                                <span class="icon_puzzle"></span>
                            </div>
                            <div class="input__item">
                                <input type="text" name="genre_2" value="<?php echo htmlspecialchars($game_data['genre_2']); ?>" placeholder="Genre 2 (Opsional)">
                                <span class="icon_puzzle"></span>
                            </div>
                            <div class="input__item">
                                <input type="text" name="genre_3" value="<?php echo htmlspecialchars($game_data['genre_3']); ?>" placeholder="Genre 3 (Opsional)">
                                <span class="icon_puzzle"></span>
                            </div>
                            <div class="input__item">
                                <textarea name="spek" required placeholder="Spesifikasi Minimum" rows="3" style="width:100%; padding-left: 50px; border: none; height: auto; margin-bottom: 20px; background: #fff; color: #b7b7b7; font-size: 15px;"><?php echo htmlspecialchars($game_data['spek']); ?></textarea>
                                <span class="icon_desktop" style="top: 13px;"></span>
                            </div>
                            <div class="input__item">
                                <input type="date" name="tanggal_rilis" value="<?php echo htmlspecialchars($game_data['tanggal_rilis']); ?>" required>
                                <span class="icon_calendar"></span>
                            </div>
                           
                            <button type="submit" class="site-btn" name="submit">Update Game</button> 
                        </form>
                             </div>
                        </div>
                               <div class="col-sm-3">
                            <div class="login__register">
                                <h3>Cancel?</h3>
                                <a href="index.php?page=admin_data_game" class="primary-btn">Back</a>
                       
                        </div>
                    </div>
                </div>
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
                        <ul></ul>
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
