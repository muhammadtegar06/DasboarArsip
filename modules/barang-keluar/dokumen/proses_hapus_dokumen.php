<?php
session_start();
// Sesuaikan dengan letak file database.php (naik 4 level berdasarkan path url ajax)
require_once "../../../config/database.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    if ($id > 0) {
        // 1. Cek apakah dokumen ini memiliki file fisik yang di-upload
        $q_cek = mysqli_query($mysqli, "SELECT file_dokumen FROM tbl_dokumen WHERE id = '$id'");

        if (mysqli_num_rows($q_cek) > 0) {
            $row = mysqli_fetch_assoc($q_cek);

            // 2. Jika ada nama filenya, hapus file tersebut dari folder server
            if (!empty($row['file_dokumen'])) {
                // Path ke folder uploads dokumen (sesuaikan jika berbeda)
                $file_path = "../../../../uploads/dokumen/" . $row['file_dokumen'];
                if (file_exists($file_path)) {
                    unlink($file_path); // Menghapus file fisik
                }
            }

            // 3. Hapus data dari Database
            $q_del = "DELETE FROM tbl_dokumen WHERE id = '$id'";
            if (mysqli_query($mysqli, $q_del)) {
                echo json_encode(['status' => 'success', 'message' => 'Dokumen berhasil dihapus.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data di database: ' . mysqli_error($mysqli)]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Data dokumen tidak ditemukan.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID tidak valid.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode request salah.']);
}
?>