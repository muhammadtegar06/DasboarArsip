<?php
session_start();
require_once "../../../config/database.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_bantex = (int) $_POST['id_bantex'];

    // Tangkap Array dari Form
    $names = isset($_POST['nama_dokumen']) ? $_POST['nama_dokumen'] : [];
    $nomors = isset($_POST['nomor_dokumen']) ? $_POST['nomor_dokumen'] : []; // FIELD BARU
    $years = isset($_POST['tahun_dokumen']) ? $_POST['tahun_dokumen'] : [];
    $kets = isset($_POST['keterangan']) ? $_POST['keterangan'] : [];       // FIELD BARU

    if (isset($_FILES['file_dokumen'])) {
        $target_dir = "../../../uploads/dokumen/";
        if (!file_exists($target_dir))
            mkdir($target_dir, 0777, true);

        $total_files = count($_FILES['file_dokumen']['name']);
        $berhasil = 0;
        $gagal = 0;

        for ($i = 0; $i < $total_files; $i++) {
            // Ambil data per baris & Sanitasi
            $nama_doc = mysqli_real_escape_string($mysqli, $names[$i]);
            $nomor = mysqli_real_escape_string($mysqli, $nomors[$i]);
            $tahun = mysqli_real_escape_string($mysqli, $years[$i]);
            $ket = mysqli_real_escape_string($mysqli, $kets[$i]);

            // File Handling
            $tmp_name = $_FILES['file_dokumen']['tmp_name'][$i];
            $name = $_FILES['file_dokumen']['name'][$i];
            $error = $_FILES['file_dokumen']['error'][$i];

            if ($error == 0 && !empty($nama_doc)) {
                $ext = pathinfo($name, PATHINFO_EXTENSION);
                $filename = "DOC_" . time() . "_" . $i . "_" . rand(100, 999) . "." . $ext;
                $target_file = $target_dir . $filename;

                if (move_uploaded_file($tmp_name, $target_file)) {
                    // QUERY UPDATE SESUAI DB
                    $q = "INSERT INTO tbl_dokumen 
                          (id_bantex, nama_dokumen, nomor_dokumen, tahun_dokumen, file_dokumen, keterangan, tgl_upload) 
                          VALUES 
                          ('$id_bantex', '$nama_doc', '$nomor', '$tahun', '$filename', '$ket', NOW())";

                    if (mysqli_query($mysqli, $q)) {
                        $berhasil++;
                    } else {
                        unlink($target_file); // Hapus file jika gagal insert DB
                        $gagal++;
                    }
                } else {
                    $gagal++;
                }
            }
        }

        if ($berhasil > 0) {
            echo json_encode(['status' => 'success', 'message' => "$berhasil dokumen berhasil disimpan."]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan. Pastikan data terisi lengkap.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Tidak ada file dikirim.']);
    }
}
?>