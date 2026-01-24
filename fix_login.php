<?php
require_once "config/database.php";

echo "<h3>Perbaikan Akun Admin...</h3>";

// 1. Cek Kolom ID (Apakah 'id' atau 'id_user'?)
$cek_kolom = mysqli_query($mysqli, "SHOW COLUMNS FROM tbl_user LIKE 'id'");
if (mysqli_num_rows($cek_kolom) > 0) {
    $pk = "id";
} else {
    $pk = "id_user";
}
echo "Nama kolom Primary Key di database Anda adalah: <b>$pk</b><br>";

// 2. Buat Password Hash Baru (Sesuai Laptop Anda)
$pass_baru = "admin123";
$hash = password_hash($pass_baru, PASSWORD_DEFAULT);

// 3. Hapus User Admin Lama (Biar bersih)
mysqli_query($mysqli, "DELETE FROM tbl_user WHERE username='admin'");

// 4. Masukkan User Admin Baru (Sesuai Struktur Tabel Baru)
// Pastikan urutan kolom sesuai dengan SQL terakhir: (id, nama_user, username, password, hak_akses, id_divisi)
$query = "INSERT INTO tbl_user ($pk, nama_user, username, password, hak_akses, id_divisi) 
          VALUES (NULL, 'Administrator', 'admin', '$hash', 'admin', 1)";

if (mysqli_query($mysqli, $query)) {
    echo "<hr><h1 style='color:green'>SUKSES!</h1>";
    echo "Akun Admin berhasil di-reset.<br>";
    echo "Username: <b>admin</b><br>";
    echo "Password: <b>admin123</b><br>";
    echo "<br>Silakan lanjut ke Langkah 2 di bawah (Update script login).";
} else {
    echo "<h1 style='color:red'>GAGAL!</h1>";
    echo "Error: " . mysqli_error($mysqli);
}
?>