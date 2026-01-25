<?php
session_start();
require_once "../../config/database.php";
header('Content-Type: application/json');

// Cek Metode Request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Tangkap Array Data dari Form
    // (Walaupun RFID di-hidden, backend tetap siap menerimanya)
    $rfids = isset($_POST['rfid']) ? $_POST['rfid'] : [];
    $lokasis = isset($_POST['lokasi']) ? $_POST['lokasi'] : [];
    $juduls = isset($_POST['judul']) ? $_POST['judul'] : [];
    $kets = isset($_POST['ket']) ? $_POST['ket'] : [];

    // Mulai Transaksi Database
    mysqli_begin_transaction($mysqli);

    try {
        // 1. Update Tabel Box (Lokasi Rak & RFID jika ada)
        foreach ($rfids as $id_box => $rfid_val) {
            $id_box = (int) $id_box;
            $rfid_safe = mysqli_real_escape_string($mysqli, $rfid_val); // Nilai lama/kosong
            $lokasi_safe = mysqli_real_escape_string($mysqli, $lokasis[$id_box]);

            $q_box = "UPDATE tbl_box SET 
                      rfid_code = '$rfid_safe', 
                      lokasi_arsip = '$lokasi_safe' 
                      WHERE id = '$id_box'";

            if (!mysqli_query($mysqli, $q_box)) {
                throw new Exception("Gagal update Box ID: $id_box. Error: " . mysqli_error($mysqli));
            }
        }

        // 2. Update Tabel Bantex (Judul & Keterangan)
        foreach ($juduls as $id_bantex => $judul_val) {
            $id_bantex = (int) $id_bantex;
            $judul_safe = mysqli_real_escape_string($mysqli, $judul_val);
            $ket_safe = mysqli_real_escape_string($mysqli, $kets[$id_bantex]);

            $q_bantex = "UPDATE tbl_bantex SET 
                         label_judul = '$judul_safe', 
                         keterangan = '$ket_safe' 
                         WHERE id = '$id_bantex'";

            if (!mysqli_query($mysqli, $q_bantex)) {
                throw new Exception("Gagal update Bantex ID: $id_bantex. Error: " . mysqli_error($mysqli));
            }
        }

        // Jika semua lancar, Commit
        mysqli_commit($mysqli);
        echo json_encode(['status' => 'success', 'message' => 'Data arsip berhasil diperbarui.']);

    } catch (Exception $e) {
        // Jika ada error, Rollback
        mysqli_rollback($mysqli);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Request']);
}
?>