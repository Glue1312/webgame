<?php
// cek_koneksi.php
echo "Mencoba meng-include koneksi.php...<br>";

// Pastikan path ke koneksi.php benar
// Jika koneksi.php ada di root, maka ini sudah benar
include 'koneksi.php';

if (isset($connect)) {
    if ($connect->connect_error) {
        echo "Koneksi Gagal: " . $connect->connect_error . "<br>";
    } else {
        echo "Koneksi Berhasil!<br>";
        echo "Host info: " . $connect->host_info . "<br>";

        // (Opsional) Coba query sederhana
        $result = $connect->query("SELECT DATABASE()");
        if ($result) {
            $row = $result->fetch_row();
            echo "Database yang aktif: " . $row[0] . "<br>";
            $result->close();
        } else {
            echo "Query gagal: " . $connect->error . "<br>";
        }
        $connect->close();
    }
} else {
    echo "Variabel \$connect tidak ditemukan setelah include koneksi.php.<br>";
}
?>
