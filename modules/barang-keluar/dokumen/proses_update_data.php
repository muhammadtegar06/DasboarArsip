<?php
session_start();
require_once "../../../config/database.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $rfids = isset($_POST['rfid']) ? $_POST['rfid'] : [];
    $lokasis = isset($_POST['lokasi']) ? $_POST['lokasi'] : [];
    $juduls = isset($_POST['judul']) ? $_POST['judul'] : [];

    $target_dir = "../../../uploads/box/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    mysqli_begin_transaction($mysqli);
    try {
        foreach ($rfids as $id_box => $rfid_val) {
            $id_box = (int) $id_box;
            $rfid_safe = mysqli_real_escape_string($mysqli, $rfid_val);
            $lokasi_safe = mysqli_real_escape_string($mysqli, $lokasis[$id_box]);

            $query_foto = "";
            if (isset($_FILES['foto_box']['name'][$id_box]) && $_FILES['foto_box']['error'][$id_box] === UPLOAD_ERR_OK) {
                $tmp_name = $_FILES['foto_box']['tmp_name'][$id_box];
                $name = $_FILES['foto_box']['name'][$id_box];
                $ext = pathinfo($name, PATHINFO_EXTENSION);

                $filename = "BOX_" . $id_box . "_" . time() . "." . $ext;
                $target_file = $target_dir . $filename;

                if (move_uploaded_file($tmp_name, $target_file)) {
                    $query_foto = ", foto_box = '$filename'";
                }
            }

            $q_box = "UPDATE tbl_box SET rfid_code = '$rfid_safe', lokasi_arsip = '$lokasi_safe' $query_foto WHERE id = '$id_box'";
            if (!mysqli_query($mysqli, $q_box))
                throw new Exception("Gagal update Box ID $id_box");
        }

        foreach ($juduls as $id_bantex => $judul_val) {
            $id_bantex = (int) $id_bantex;
            $judul_safe = mysqli_real_escape_string($mysqli, $judul_val);

            // Keterangan dihapus dari query ini
            $q_bantex = "UPDATE tbl_bantex SET label_judul = '$judul_safe' WHERE id = '$id_bantex'";
            if (!mysqli_query($mysqli, $q_bantex))
                throw new Exception("Gagal update Bantex ID $id_bantex");
        }

        mysqli_commit($mysqli);
        echo json_encode(['status' => 'success', 'message' => 'Data berhasil disimpan.']);

    } catch (Exception $e) {
        mysqli_rollback($mysqli);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode request tidak diizinkan.']);
}
?>