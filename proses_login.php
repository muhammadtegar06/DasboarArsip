<?php
require_once "config/database.php";

$username = mysqli_real_escape_string($mysqli, $_POST['username']);
$password = mysqli_real_escape_string($mysqli, $_POST['password']);

// Cek Username
$query = mysqli_query($mysqli, "SELECT * FROM tbl_user WHERE username='$username'");

if (mysqli_num_rows($query) > 0) {
	$data = mysqli_fetch_assoc($query);

	// Cek Password
	if (password_verify($password, $data['password'])) {
		session_start();

		// PENTING: Kita pakai logika cek kolom otomatis agar tidak error lagi
		// Jika kolom 'id' ada, pakai 'id'. Jika tidak, pakai 'id_user'.
		if (isset($data['id'])) {
			$_SESSION['id_user'] = $data['id'];
		} elseif (isset($data['id_user'])) {
			$_SESSION['id_user'] = $data['id_user'];
		}

		// Set Session Lainnya
		$_SESSION['nama_user'] = $data['nama_user'];
		$_SESSION['username'] = $data['username'];
		$_SESSION['hak_akses'] = $data['hak_akses'];
		$_SESSION['id_divisi'] = $data['id_divisi']; // Penting untuk filter data divisi

		// Login Sukses -> Masuk Dashboard
		header('location: main.php?module=dashboard&pesan=1');
	} else {
		// Password Salah
		header('location: login.php?pesan=1');
	}
} else {
	// Username Tidak Ditemukan
	header('location: login.php?pesan=1');
}
?>