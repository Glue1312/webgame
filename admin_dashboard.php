<?php
// Selalu mulai sesi di baris paling atas sebelum output apapun
// Di index.php, session_start() sudah ada, jadi baris ini mungkin tidak diperlukan
// jika file ini selalu di-include melalui index.php. Namun, tidak ada salahnya
// memastikannya ada jika file ini bisa diakses secara langsung (sebaiknya tidak).
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autentikasi Admin (ini seharusnya sudah ditangani oleh index.php,
// tapi sebagai lapisan tambahan jika file diakses langsung)
if (!isset($_SESSION['username']) || !isset($_SESSION['jenis_login']) || $_SESSION['jenis_login'] != 'admin') {
    // Jika ingin memberi pesan spesifik untuk akses langsung yang tidak diizinkan:
    // header("location: index.php?page=admin_login&pesan=akses_ditolak");
    // Atau, jika ini adalah bagian dari sistem routing index.php, biarkan index.php yang menangani:
    header("location: index.php?page=admin_login&pesan=belum_login");
    exit;
}

// Include file koneksi database
// Pastikan path ini benar relatif terhadap index.php jika di-include dari sana,
// atau relatif terhadap file ini jika bisa diakses langsung.
// Lebih baik menggunakan path absolut atau path relatif yang konsisten.
// Menggunakan BASE_PATH dari index.php akan lebih baik jika file ini di-include.
// Untuk contoh ini, kita asumsikan koneksi.php ada di direktori yang sama.
include 'koneksi.php'; //

// Inisialisasi variabel untuk total penghasilan
$total_penghasilan_display = "Rp. 0"; // Default value

// Ambil total penghasilan dari database
// Menggunakan prepared statement untuk keamanan jika ada input,
// tapi untuk query ini tidak ada input pengguna langsung, jadi query langsung aman.
// Namun, konsistensi penggunaan prepared statement adalah praktik yang baik.
$sql_penghasilan = "SELECT SUM(harga) AS total_harga FROM transaksi";
$query_penghasilan = mysqli_query($connect, $sql_penghasilan);

if ($query_penghasilan) {
    $data_penghasilan = mysqli_fetch_assoc($query_penghasilan);
    $total_penghasilan_db = $data_penghasilan['total_harga'] ? (int)$data_penghasilan['total_harga'] : 0;

    // Format untuk tampilan
    $total_penghasilan_display = "Rp. " . number_format($total_penghasilan_db, 0, ',', '.');

    // Update total penghasilan di tabel admin (jika masih diperlukan)
    // Sebaiknya total penghasilan dihitung secara dinamis daripada disimpan dan diupdate.
    // Jika tetap ingin diupdate, gunakan prepared statement.
    $stmt_update_admin = mysqli_prepare($connect, "UPDATE admin SET total_penghasilan = ? WHERE username = ?");
    if ($stmt_update_admin) {
        mysqli_stmt_bind_param($stmt_update_admin, "is", $total_penghasilan_db, $_SESSION['username']);
        mysqli_stmt_execute($stmt_update_admin);
        mysqli_stmt_close($stmt_update_admin);
    } else {
        error_log("Admin Dashboard: Gagal menyiapkan statement update penghasilan admin: " . mysqli_error($connect));
        // Jangan tampilkan mysqli_error ke pengguna
    }
} else {
    error_log("Admin Dashboard: Gagal mengambil data penghasilan: " . mysqli_error($connect));
    // Jangan tampilkan mysqli_error ke pengguna
    // $total_penghasilan_display akan tetap "Rp. 0"
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
    <title>LGS Dashboard Admin</title>
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
                            <img src="img/1.png" alt="Logo Toko">
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
                                <li><a href="#">Hallo <?php echo htmlspecialchars($_SESSION['username']); // Pencegahan XSS ?> <span class="arrow_carrot-down"></span></a>
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
                    <div class="section-title">
                        <h4>Welcome Admin</h4>
                    </div>
                </div>
            </div>
            <div class="hero__slider owl-carousel">
                <div class="hero__items set-bg" data-setbg="img/head/admin.jpg">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="hero__text">
                                <h2>Penghasilan</h2>
                                <p><?php echo htmlspecialchars($total_penghasilan_display); // Pencegahan XSS ?></p>
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
                                <div class="section-title">
                                    <h4>All Games</h4>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            // Ambil data game
                            // Query ini tidak memiliki input pengguna, jadi query langsung relatif aman,
                            // tapi konsistensi penggunaan prepared statements lebih baik.
                            $sql_games = "SELECT id_game, nama_game, harga, genre_1, genre_2, genre_3 FROM game";
                            $query_games = mysqli_query($connect, $sql_games);

                            if ($query_games && mysqli_num_rows($query_games) > 0) {
                                while ($data_game = mysqli_fetch_assoc($query_games)) {
                            ?>
                                    <div class="col-lg-4 col-md-6 col-sm-6">
                                        <div class="product__item">
                                            <div class="product__item__pic set-bg" data-setbg="img/game/<?php echo htmlspecialchars($data_game['id_game']); ?>.jpg">
                                                <div class="ep">Rp. <?php echo number_format($data_game['harga'], 0, ',', '.'); // Format harga ?></div>
                                            </div>
                                            <div class="product__item__text">
                                                <ul>
                                                    <li><?php echo htmlspecialchars($data_game['genre_1']); ?></li>
                                                    <li><?php echo htmlspecialchars($data_game['genre_2']); ?></li>
                                                    <li><?php echo htmlspecialchars($data_game['genre_3']); ?></li>
                                                </ul>
                                                <h5><a href="index.php?page=user_view_game&id_game=<?php echo urlencode($data_game['id_game']); // URL Encoding untuk parameter ?>"><?php echo htmlspecialchars($data_game['nama_game']); ?></a></h5>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            } else {
                                if (!$query_games) {
                                    error_log("Admin Dashboard: Gagal mengambil data game: " . mysqli_error($connect));
                                }
                                echo "<div class='col-12'><p>Tidak ada game yang tersedia saat ini.</p></div>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer class="footer">
        <div class="page-up">
            <a href="#" id="scrollToTopButton"><span class="arrow_carrot-up"></span></a>
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
                            </ul>
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
