<?php
session_start();
require_once "../../config/database.php";

if (isset($_GET['id']) && isset($_GET['aksi'])) {
    $id_pengajuan = mysqli_real_escape_string($mysqli, $_GET['id']);
    $aksi = mysqli_real_escape_string($mysqli, $_GET['aksi']);

    // Tentukan Status Baru
    if ($aksi == 'terima') {
        $status_header = 'Diterima';
        $status_box = 'Diterima'; // Atau 'Siap Input'
    } else {
        $status_header = 'Ditolak';
        $status_box = 'Ditolak';
    }

    // Mulai Transaksi agar data sinkron
    mysqli_begin_transaction($mysqli);

    try {
        // 1. Update Status Header (Pengajuan)
        $q1 = "UPDATE tbl_pengajuan SET status = '$status_header' WHERE id = '$id_pengajuan'";
        if (!mysqli_query($mysqli, $q1))
            throw new Exception("Gagal update header.");

        // 2. Update Status Detail (Box) - Agar status box ikut berubah
        $q2 = "UPDATE tbl_box SET status_acc = '$status_box' WHERE id_pengajuan = '$id_pengajuan'";
        if (!mysqli_query($mysqli, $q2))
            throw new Exception("Gagal update detail box.");

        mysqli_commit($mysqli);

        // Redirect Sukses
        echo "<script>
                alert('Berhasil! Data pengajuan telah di-" . strtoupper($aksi) . "');
                window.location.href='../../main.php?module=barang_masuk';
              </script>";

    } catch (Exception $e) {
        mysqli_rollback($mysqli);
        echo "<script>
                alert('Gagal: " . $e->getMessage() . "');
                window.location.href='../../main.php?module=barang_masuk';
              </script>";
    }
}
?>