<?php
session_start();
// Sesuaikan path ke database.php (Naik 3 level)
require_once "../../config/database.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id_pengajuan']) ? (int) $_POST['id_pengajuan'] : 0;
    $status = isset($_POST['status_baru']) ? mysqli_real_escape_string($mysqli, $_POST['status_baru']) : '';

    if ($id > 0 && !empty($status)) {
        // Query Update
        $query = "UPDATE tbl_pengajuan SET status = '$status' WHERE id = '$id'";

        if (mysqli_query($mysqli, $query)) {
            echo json_encode(['status' => 'success', 'message' => 'Status pengiriman berhasil diperbarui.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . mysqli_error($mysqli)]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak valid.']);
    }
}
?>