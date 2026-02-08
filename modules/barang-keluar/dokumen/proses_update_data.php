<?php
session_start();
// Sesuaikan path ke database.php
require_once "../../../config/database.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Tangkap Data Array
    $rfids = isset($_POST['rfid']) ? $_POST['rfid'] : [];
    $lokasis = isset($_POST['lokasi']) ? $_POST['lokasi'] : [];
    $juduls = isset($_POST['judul']) ? $_POST['judul'] : [];

    // Keterangan Bantex (Opsional, jika mau dihandle walau tidak ada kolomnya di DB sementara)
    // $kets = isset($_POST['ket']) ? $_POST['ket'] : [];

    mysqli_begin_transaction($mysqli);
    try {
        // 1. Update Box (RFID & Lokasi)
        foreach ($rfids as $id_box => $rfid_val) {
            $id_box = (int) $id_box;
            $rfid_safe = mysqli_real_escape_string($mysqli, $rfid_val);
            $lokasi_safe = mysqli_real_escape_string($mysqli, $lokasis[$id_box]);

            // Update tabel box
            $q_box = "UPDATE tbl_box SET rfid_code = '$rfid_safe', lokasi_arsip = '$lokasi_safe' WHERE id = '$id_box'";
            if (!mysqli_query($mysqli, $q_box))
                throw new Exception("Gagal update Box ID $id_box: " . mysqli_error($mysqli));
        }

        // 2. Update Bantex (Label Judul)
        foreach ($juduls as $id_bantex => $judul_val) {
            $id_bantex = (int) $id_bantex;
            $judul_safe = mysqli_real_escape_string($mysqli, $judul_val);

            // Update tabel bantex
            $q_bantex = "UPDATE tbl_bantex SET label_judul = '$judul_safe' WHERE id = '$id_bantex'";
            if (!mysqli_query($mysqli, $q_bantex))
                throw new Exception("Gagal update Bantex ID $id_bantex: " . mysqli_error($mysqli));
        }

        mysqli_commit($mysqli);
        echo json_encode(['status' => 'success', 'message' => 'Data berhasil disimpan.']);

    } catch (Exception $e) {
        mysqli_rollback($mysqli);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>