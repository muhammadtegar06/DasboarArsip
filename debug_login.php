<?php
// Tampilkan semua error biar ketahuan penyakitnya
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "config/database.php";

echo "<h2>🕵️ DEBUG MODE LOGIN</h2>";
echo "<hr>";

// 1. Cek Koneksi Database
if ($mysqli->connect_error) {
    die("<h3 style='color:red'>❌ Koneksi Database GAGAL: " . $mysqli->connect_error . "</h3>");
}
echo "<p>✅ Koneksi Database: OK</p>";

// 2. Cek Apakah User 'admin' Ada?
$username_target = 'admin';
$query = mysqli_query($mysqli, "SELECT * FROM tbl_user WHERE username = '$username_target'");
$user_db = mysqli_fetch_assoc($query);

if (!$user_db) {
    die("<h3 style='color:red'>❌ User '$username_target' TIDAK DITEMUKAN di Database!</h3>
         <p>Solusi: Jalankan ulang script <b>fix_login.php</b> yang saya berikan sebelumnya.</p>");
}

// 3. Cek Nama Kolom ID (Penyebab error 'Undefined Index')
$id_val = "";
if (isset($user_db['id'])) {
    $id_val = $user_db['id'] . " (Nama kolom: id)";
} elseif (isset($user_db['id_user'])) {
    $id_val = $user_db['id_user'] . " (Nama kolom: id_user)";
} else {
    $id_val = "<span style='color:red'>TIDAK DITEMUKAN (Cek struktur tabel!)</span>";
}

echo "<p>✅ User '$username_target' ditemukan.</p>";
echo "<ul style='background:#eee; padding:10px;'>";
echo "<li><b>ID User:</b> " . $id_val . "</li>";
echo "<li><b>Hash di DB:</b> " . $user_db['password'] . "</li>";
echo "</ul>";

// 4. Simulasi Cek Password 'admin123'
$password_input = "admin123";
echo "<p>🔄 Sedang mencocokkan dengan password input: <b>$password_input</b> ...</p>";

if (password_verify($password_input, $user_db['password'])) {
    echo "<h3 style='color:green'>✅ PASSWORD COCOK! (Logic Benar)</h3>";

    // 5. Cek Apakah Session Bisa Disimpan?
    session_start();
    $_SESSION['test_debug'] = "Bisa Masuk";

    if (isset($_SESSION['test_debug'])) {
        echo "<p>✅ Session berhasil disimpan di server.</p>";
        echo "<div style='border: 2px dashed green; padding: 15px; margin-top:10px;'>";
        echo "<h3>KESIMPULAN:</h3>";
        echo "Akun & Password <b>SEHAT 100%</b>.<br>";
        echo "Jika Anda masih gagal login, berarti masalahnya ada di file <b>main.php</b> (Pengecekan Session Salah) atau file <b>dashboard</b>.";
        echo "</div>";
    } else {
        echo "<h3 style='color:red'>❌ GAGAL MENYIMPAN SESSION!</h3>";
        echo "<p>Login benar, tapi Server/Browser menolak menyimpan sesi Anda.</p>";
    }

} else {
    echo "<h3 style='color:red'>❌ PASSWORD SALAH! (Hash Tidak Cocok)</h3>";
    echo "<p>Hash di database berbeda dengan 'admin123'.</p>";
    echo "<p><b>Solusi:</b> Jalankan lagi <b>fix_login.php</b> untuk reset password.</p>";
}
?>