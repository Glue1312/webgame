<?php
session_start();
if (!(isset($_SESSION['username']) && isset($_SESSION['jenis_login']) && $_SESSION['jenis_login'] == 'admin')) {
    header("location: index.php?page=admin_login&pesan=belum_login_admin");
    exit;
}
include 'koneksi.php';

$id_game_to_edit = isset($_GET['id_game']) ? intval($_GET['id_game']) : 0;

if ($id_game_to_edit <= 0) {
    header("location: index.php?page=admin_data_game&pesan=id_game_tidak_valid");
    exit;
}

$query_game_data = mysqli_query($connect, "SELECT * FROM game WHERE id_game = $id_game_to_edit");
if ($query_game_data && mysqli_num_rows($query_game_data) > 0) {
    $data_game_edit = mysqli_fetch_array($query_game_data);
} else {
    header("location: index.php?page=admin_data_game&pesan=game_tidak_ditemukan");
    exit;
}

$bucketNameEnv = getenv('GCS_BUCKET_NAME') ?: 'ta-tcc';
$currentImagePath = "img/placeholder.jpg"; // Default jika tidak ada gambar

// Asumsikan nama file gambar adalah id_game.ekstensi
// Anda perlu cara untuk mengetahui ekstensi yang benar jika bervariasi
// Untuk contoh, kita coba beberapa ekstensi umum. Ini tidak ideal untuk produksi.
$possible_extensions_edit = ['jpg', 'png', 'jpeg', 'gif'];
foreach ($possible_extensions_edit as $ext_edit) {
    $tempImagePath = "https://storage.googleapis.com/{$bucketNameEnv}/img/game/" . rawurlencode($data_game_edit['id_game']) . "." . $ext_edit;
    // Cara sederhana untuk cek (tidak selalu akurat & bisa lambat, idealnya simpan nama file lengkap di DB)
    // $headers_edit = @get_headers($tempImagePath);
    // if ($headers_edit && strpos($headers_edit[0], '200')) {
    //    $currentImagePath = $tempImagePath;
    //    break;
    // }
    // Untuk sementara, kita asumsikan .jpg jika tidak ada info ekstensi di DB
    if ($ext_edit == 'jpg') {
        $currentImagePath = $tempImagePath;
    }
}
// Jika Anda menyimpan nama_file_gambar lengkap di DB (misal: 123.png):
// if (!empty($data_game_edit['nama_file_gambar'])) {
//    $safeImageFileName = basename($data_game_edit['nama_file_gambar']);
//    $currentImagePath = "https://storage.googleapis.com/{$bucketNameEnv}/img/game/" . rawurlencode($safeImageFileName);
// }

?>
<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="UTF-8">
    <title>Edit Game: <?php echo htmlspecialchars($data_game_edit['nama_game']); ?> - LGS</title>
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

    <section class="normal-breadcrumb set-bg" data-setbg="img/normal-breadcrumb.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="normal__breadcrumb__text">
                        <h2>Edit Data Game</h2>
                        <p>Administrator: <?php echo htmlspecialchars($data_game_edit['nama_game']); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="login spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <div class="login__form">
                        <h3>Edit Game Details</h3><br>
                        <form method="POST" action="index.php?page=admin_edit_game_proses" enctype="multipart/form-data">
                            <input type="hidden" name="id_game" value="<?php echo htmlspecialchars($data_game_edit['id_game']); ?>">
                            <div class="input__item">
                                <label for="nama_game">ID Game (Read-only):</label>
                                <input type="text" value="<?php echo htmlspecialchars($data_game_edit['id_game']); ?>" readonly>
                                <span class="icon_key"></span>
                            </div>
                            <div class="input__item">
                                <label for="nama_game">Nama Game:</label>
                                <input type="text" id="nama_game" name="nama_game" value="<?php echo htmlspecialchars($data_game_edit['nama_game']); ?>" required placeholder="Nama Game">
                                <span class="icon_document"></span>
                            </div>
                            <div class="input__item">
                                <label for="nama_dev">Developer:</label>
                                <input type="text" id="nama_dev" name="nama_dev" value="<?php echo htmlspecialchars($data_game_edit['nama_dev']); ?>" required placeholder="Developer">
                                <span class="icon_briefcase"></span>
                            </div>
                            <div class="input__item">
                                <label for="harga">Harga (Rp):</label>
                                <input type="number" id="harga" name="harga" value="<?php echo htmlspecialchars($data_game_edit['harga']); ?>" required placeholder="Harga">
                                <span class="icon_wallet"></span>
                            </div>
                            <div class="input__item">
                                <label for="genre_1">Genre 1:</label>
                                <input type="text" id="genre_1" name="genre_1" value="<?php echo htmlspecialchars($data_game_edit['genre_1']); ?>" required placeholder="Genre 1">
                                <span class="icon_tag"></span>
                            </div>
                            <div class="input__item">
                                <label for="genre_2">Genre 2 (Opsional):</label>
                                <input type="text" id="genre_2" name="genre_2" value="<?php echo htmlspecialchars($data_game_edit['genre_2']); ?>" placeholder="Genre 2">
                                <span class="icon_tag"></span>
                            </div>
                            <div class="input__item">
                                <label for="genre_3">Genre 3 (Opsional):</label>
                                <input type="text" id="genre_3" name="genre_3" value="<?php echo htmlspecialchars($data_game_edit['genre_3']); ?>" placeholder="Genre 3">
                                <span class="icon_tag"></span>
                            </div>
                            <div class="input__item">
                                <label for="spek">Spesifikasi:</label>
                                <textarea id="spek" name="spek" rows="3" required placeholder="Spesifikasi Minimum" style="width:100%; padding:10px; border:1px solid #e1e1e1; border-radius:5px;"><?php echo htmlspecialchars($data_game_edit['spek']); ?></textarea>
                            </div>
                            <div class="input__item">
                                <label for="tanggal_rilis">Tanggal Rilis:</label>
                                <input type="date" id="tanggal_rilis" name="tanggal_rilis" value="<?php echo htmlspecialchars($data_game_edit['tanggal_rilis']);?>" required>
                                <span class="icon_calendar"></span>
                            </div>
                             <div class="form-group mb-3">
                                <label for="fileToUpload">Gambar Game Saat Ini:</label><br>
                                <img src="<?php echo htmlspecialchars($currentImagePath); ?>" alt="Gambar Game Saat Ini" style="max-width: 200px; max-height: 200px; margin-bottom: 10px; border:1px solid #ddd; object-fit: cover;">
                                <br>
                                <label for="fileToUpload">Ganti Gambar (Opsional, .jpg, .jpeg, .png):</label>
                                <input class="form-control" type="file" id="fileToUpload" name="fileToUpload">
                            </div>
                            <button type="submit" class="site-btn" name="submit">Update Game</button>
                        </form>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="login__register">
                        <h3>Batal Edit?</h3>
                        <a href="index.php?page=admin_data_game" class="primary-btn">Kembali ke Data Game</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer"></footer>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
