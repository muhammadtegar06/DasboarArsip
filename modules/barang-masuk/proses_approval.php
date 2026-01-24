<?php
session_start();
// Pastikan path ke database benar
require_once "../../config/database.php";

// Cek apakah ada ID dan Aksi yang dikirim
if (isset($_GET['id']) && isset($_GET['aksi'])) {
    $id_pengajuan = mysqli_real_escape_string($mysqli, $_GET['id']);
    $aksi = mysqli_real_escape_string($mysqli, $_GET['aksi']);

    // 1. Tentukan Status Baru Berdasarkan Aksi
    if ($aksi == 'terima') {
        $status_header = 'Disetujui'; // Status untuk tabel pengajuan
        $status_box = 'Diterima';  // Status untuk tabel box
    } else {
        $status_header = 'Ditolak';
        $status_box = 'Ditolak';
    }

    // 2. Mulai Transaksi (Agar data aman & sinkron)
    mysqli_begin_transaction($mysqli);

    try {
        // A. Update Status Header (tbl_pengajuan)
        $query_header = "UPDATE tbl_pengajuan SET status = '$status_header' WHERE id = '$id_pengajuan'";
        if (!mysqli_query($mysqli, $query_header)) {
            throw new Exception("Gagal mengupdate status pengajuan.");
        }

        // B. Update Status Box (tbl_box) - Opsional, agar status box ikut berubah
        $query_box = "UPDATE tbl_box SET status_acc = '$status_box' WHERE id_pengajuan = '$id_pengajuan'";
        if (!mysqli_query($mysqli, $query_box)) {
            throw new Exception("Gagal mengupdate status box.");
        }

        // Jika semua lancar, simpan perubahan
        mysqli_commit($mysqli);

        // --- PERBAIKAN UTAMA DISINI ---
        // Jangan pakai echo "<script>alert...</script>" lagi.
        // Langsung redirect (lempar balik) ke halaman utama.
        header("Location: ../../main.php?module=barang_masuk");
        exit();

    } catch (Exception $e) {
        // Jika error, batalkan semua perubahan
        mysqli_rollback($mysqli);

        // Kalau error, baru kita tampilkan alert manual (Jaga-jaga)
        echo "<script>
                alert('Gagal: " . $e->getMessage() . "');
                window.location.href='../../main.php?module=barang_masuk';
              </script>";
    }
} else {
    // Jika diakses langsung tanpa ID
    header("Location: ../../main.php?module=barang_masuk");
}
?>