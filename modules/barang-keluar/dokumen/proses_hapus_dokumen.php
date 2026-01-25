<?php
session_start();
require_once "../../config/database.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_dokumen = (int) $_POST['id'];

    // 1. Ambil nama file sebelum dihapus (untuk hapus fisik)
    $q_cek = mysqli_query($mysqli, "SELECT file_dokumen FROM tbl_dokumen WHERE id = '$id_dokumen'");
    $data = mysqli_fetch_assoc($q_cek);

    if ($data) {
        $file_name = $data['file_dokumen'];
        $file_path = "../../uploads/dokumen/" . $file_name;

        // 2. Hapus dari Database
        $q_del = "DELETE FROM tbl_dokumen WHERE id = '$id_dokumen'";
        if (mysqli_query($mysqli, $q_del)) {

            // 3. Hapus File Fisik (Jika ada)
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            echo json_encode(['status' => 'success', 'message' => 'Dokumen dihapus.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus database.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan.']);
    }
}
?>