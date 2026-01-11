<?php
session_start();
require_once "../../config/database.php";

if (isset($_GET['id'])) {
    $id_pengajuan = $_GET['id'];

    // Validasi: Cek apakah status masih Pending?
    // (Opsional: Agar user nakal tidak bisa hapus yang sudah diapprove via URL)
    $cek = mysqli_query($mysqli, "SELECT status FROM tbl_pengajuan WHERE id='$id_pengajuan'");
    $data = mysqli_fetch_assoc($cek);

    if ($data['status'] == 'Pending') {
        // Hapus Data Pengajuan
        // (Data Box, Bantex, Dokumen otomatis terhapus karena kita pakai ON DELETE CASCADE di database)
        $query_hapus = mysqli_query($mysqli, "DELETE FROM tbl_pengajuan WHERE id='$id_pengajuan'");

        if ($query_hapus) {
            // Redirect sukses
            echo "<script>alert('Data berhasil dihapus'); window.location.href='../../main.php?module=barang_masuk';</script>";
        } else {
            echo "Gagal menghapus: " . mysqli_error($mysqli);
        }
    } else {
        echo "<script>alert('Data yang sudah Diterima/Ditolak tidak bisa dihapus!'); window.location.href='../../main.php?module=barang_masuk';</script>";
    }
}
?>