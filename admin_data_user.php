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
    <title>Data User - LGS</title>
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
                                <li><a href="index.php?page=admin_data_game">Games </a></li>
                                <li><a href="index.php?page=admin_data_transaksi">Transaksi</a></li>
                                <li class="active"><a href="index.php?page=admin_data_user">User</a></li>
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
                                <div class="section-title"><h4>Data User</h4></div>
                            </div>
                             <div class="col-lg-4 col-md-4 col-sm-4 text-right">
                                <a href="index.php?page=admin_tambah_user" class="primary-btn" style="margin-bottom: 20px;"><b>+ Tambah User</b></a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-secondary table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID User</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>No Telp</th>
                                        <th>Tanggal Dibuat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $sql_users = "SELECT * FROM user ORDER BY id_user DESC";
                                $query_users = mysqli_query($connect, $sql_users);
                                if($query_users && mysqli_num_rows($query_users) > 0){
                                    while ($data = mysqli_fetch_array($query_users)) {
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($data['id_user']); ?></td>
                                        <td><?php echo htmlspecialchars($data['username']); ?></td>
                                        <td><?php echo htmlspecialchars($data['email']); ?></td>
                                        <td><?php echo htmlspecialchars($data['no_telp']); ?></td>
                                        <td><?php echo htmlspecialchars(date('d M Y, H:i:s', strtotime($data['tanggal_dibuat']))); ?></td>
                                        <td>
                                            <a href="index.php?page=admin_hapus_user&id_user=<?php echo $data['id_user']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini? Seluruh data transaksi user ini juga akan terhapus (jika ada relasi ON DELETE CASCADE).');">Hapus</a>
                                        </td>
                                    </tr>
                                <?php
                                    }
                                } else {
                                     echo "<tr><td colspan='6' class='text-center'>Belum ada data user.</td></tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
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
