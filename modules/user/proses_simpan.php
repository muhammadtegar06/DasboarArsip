<?php
session_start();

if (empty($_SESSION['username']) && empty($_SESSION['password'])) {
	header('location: ../../login.php?pesan=2');
} else {
	require_once "../../config/database.php";

	if (isset($_POST['simpan'])) {
		$nama_user = mysqli_real_escape_string($mysqli, trim($_POST['nama_user']));
		$username = mysqli_real_escape_string($mysqli, trim($_POST['username']));
		$password = mysqli_real_escape_string($mysqli, $_POST['password']);
		$hak_akses = mysqli_real_escape_string($mysqli, trim($_POST['hak_akses']));
		$id_divisi = (int) $_POST['id_divisi'];

		$option = ['cost' => 12];
		$password_hash = password_hash($password, PASSWORD_DEFAULT, $option);

		$query = mysqli_query($mysqli, "SELECT username FROM tbl_user WHERE username='$username'")
			or die('Ada kesalahan pada query : ' . mysqli_error($mysqli));
		$rows = mysqli_num_rows($query);

		if ($rows <> 0) {
			header("location: ../../main.php?module=user&pesan=4&username=$username");
		} else {
			// Query Insert diperbarui dengan id_divisi
			$insert = mysqli_query($mysqli, "INSERT INTO tbl_user(nama_user, username, password, hak_akses, id_divisi)
                                            VALUES('$nama_user', '$username', '$password_hash', '$hak_akses', '$id_divisi')")
				or die('Ada kesalahan pada query insert : ' . mysqli_error($mysqli));
			if ($insert) {
				header('location: ../../main.php?module=user&pesan=1');
			}
		}
	}
}
?>