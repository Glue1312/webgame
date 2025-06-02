<?php
session_start();
if (!(isset($_SESSION['username']) && isset($_SESSION['jenis_login']) && $_SESSION['jenis_login'] == 'admin')) {
    header("location: index.php?page=admin_login&pesan=belum_login_admin");
    exit;
}
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="UTF-8">
    <title>Admin Data Game - LGS</title>
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

    <section class="product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="trending__product">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="section-title"><h4>Data Games</h4></div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 text-right">
                                <a href="index.php?page=admin_tambah_game" class="primary-btn" style="margin-bottom: 20px;"><b>+ Tambah Data Game</b></a>
                            </div>
                        </div>
                        <div class="row">
                            <?php
                            $sql_all_games    = "SELECT * FROM game ORDER BY id_game DESC";
                            $query_all_games  = mysqli_query($connect, $sql_all_games);
                            $bucketNameEnv    = getenv('GCS_BUCKET_NAME') ?: 'ta-tcc';

                            if($query_all_games && mysqli_num_rows($query_all_games) > 0){
                                while ($data = mysqli_fetch_array($query_all_games)) {
                                    // Asumsi ekstensi gambar adalah .jpg jika tidak ada kolom nama_file_gambar
                                    // Jika Anda ingin fleksibel dengan ekstensi, Anda perlu cara untuk mengetahuinya
                                    // (misalnya, menyimpan nama file lengkap di DB atau mencoba beberapa ekstensi).
                                    // Untuk saat ini, kita akan coba .jpg, .png, .jpeg
                                    $possible_extensions = ['jpg', 'png', 'jpeg'];
                                    $imagePath = "img/placeholder.jpg"; // Default

                                    // Cari ekstensi yang valid
                                    // Ini BUKAN cara yang paling efisien jika Anda memiliki banyak gambar/ekstensi.
                                    // Lebih baik menyimpan nama file lengkap (id_game.ekstensi) di DB.
                                    // Namun, jika Anda TIDAK mau ada kolom DB baru, ini adalah salah satu pendekatan.
                                    foreach ($possible_extensions as $ext) {
                                        $tempPath = "https://storage.googleapis.com/{$bucketNameEnv}/img/game/" . rawurlencode($data['id_game']) . "." . $ext;
                                        // Cara sederhana untuk cek apakah file ada (tidak selalu akurat dan bisa lambat):
                                        // $headers = @get_headers($tempPath);
                                        // if ($headers && strpos($headers[0], '200')) {
                                        //    $imagePath = $tempPath;
                                        //    break;
                                        // }
                                        // Untuk App Engine, lebih baik asumsikan satu format atau simpan nama lengkap di DB.
                                        // Untuk saat ini, kita default ke .jpg
                                        if ($ext == 'jpg') { // Asumsikan default adalah JPG
                                            $imagePath = $tempPath;
                                        }
                                    }
                                    // Jika Anda YAKIN semua gambar akan .jpg, sederhanakan:
                                    // $imagePath = "https://storage.googleapis.com/{$bucketNameEnv}/img/game/" . rawurlencode($data['id_game']) . ".jpg";

                            ?>
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="product__item">
                                        <div class="product__item__pic set-bg" data-setbg="<?php echo htmlspecialchars($imagePath); ?>">
                                            <div class="ep">Rp. <?php echo htmlspecialchars(number_format($data['harga'], 0, ',', '.')); ?></div>
                                        </div>
                                        <div class="product__item__text">
                                            <ul>
                                                <li>ID Game : <?php echo htmlspecialchars($data['id_game']); ?> </li>
                                                <li>Developer : <?php echo htmlspecialchars($data['nama_dev']); ?></li>
                                                <li>Genre : <?php echo htmlspecialchars($data['genre_1'] . (!empty($data['genre_2']) ? ', ' . $data['genre_2'] : '') . (!empty($data['genre_3']) ? ', ' . $data['genre_3'] : '')); ?></li>
                                                <li>Spek : <?php echo htmlspecialchars(substr($data['spek'], 0, 50)) . (strlen($data['spek']) > 50 ? '...' : ''); ?></li>
                                                <li>Rilis : <?php echo htmlspecialchars(date('d M Y', strtotime($data['tanggal_rilis']))); ?></li>
                                            </ul>
                                            <h5><a href="index.php?page=user_view_game&id_game=<?php echo $data['id_game']; ?>"><?php echo htmlspecialchars($data['nama_game']); ?></a></h5>
                                            <ul style="margin-top: 10px;">
                                                <li style="margin-right: 10px;"><a href="index.php?page=admin_edit_game&id_game=<?php echo $data['id_game']; ?>" class="btn btn-sm btn-info">Edit</a></li>
                                                <li><a href="index.php?page=admin_hapus_game&id_game=<?php echo $data['id_game']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus game ini?');">Hapus</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                }
                            } else {
                                echo "<div class='col-12'><p>Belum ada data game.</p></div>";
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
    <script src="js/main.js"></script>
</body>
</html>
