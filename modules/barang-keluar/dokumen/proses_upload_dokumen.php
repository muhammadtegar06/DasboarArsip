<?php
session_start();
require_once "../../../config/database.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_bantex = (int) $_POST['id_bantex'];

    $names = isset($_POST['nama_dokumen']) ? $_POST['nama_dokumen'] : [];
    $nomors = isset($_POST['nomor_dokumen']) ? $_POST['nomor_dokumen'] : [];
    $years = isset($_POST['tahun_dokumen']) ? $_POST['tahun_dokumen'] : [];
    $kets = isset($_POST['keterangan']) ? $_POST['keterangan'] : [];

    $target_dir = "../../../uploads/dokumen/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $total_rows = count($names);
    $berhasil = 0;
    $gagal = 0;

    for ($i = 0; $i < $total_rows; $i++) {
        $nama_doc = mysqli_real_escape_string($mysqli, $names[$i]);
        $nomor = mysqli_real_escape_string($mysqli, $nomors[$i]);
        $tahun = mysqli_real_escape_string($mysqli, $years[$i]);
        $ket = mysqli_real_escape_string($mysqli, $kets[$i]);

        if (!empty($nama_doc) && !empty($nomor) && !empty($tahun)) {
            $filename = "";

            if (isset($_FILES['file_dokumen']['name'][$i]) && $_FILES['file_dokumen']['error'][$i] === UPLOAD_ERR_OK) {
                $name = $_FILES['file_dokumen']['name'][$i];
                $tmp_name = $_FILES['file_dokumen']['tmp_name'][$i];
                $ext = pathinfo($name, PATHINFO_EXTENSION);
                $filename = "DOC_" . time() . "_" . $i . "_" . rand(100, 999) . "." . $ext;
                $target_file = $target_dir . $filename;

                if (!move_uploaded_file($tmp_name, $target_file)) {
                    $filename = "";
                }
            }

            $q = "INSERT INTO tbl_dokumen 
                  (id_bantex, nama_dokumen, nomor_dokumen, tahun_dokumen, file_dokumen, keterangan, tgl_upload) 
                  VALUES 
                  ('$id_bantex', '$nama_doc', '$nomor', '$tahun', '$filename', '$ket', NOW())";

            if (mysqli_query($mysqli, $q)) {
                $berhasil++;
            } else {
                if ($filename != "" && file_exists($target_file)) {
                    unlink($target_file);
                }
                $gagal++;
            }
        } else {
            $gagal++;
        }
    }

    if ($berhasil > 0) {
        echo json_encode(['status' => 'success', 'message' => "$berhasil dokumen berhasil disimpan."]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan. Pastikan field Wajib sudah terisi.']);
    }
}
?>