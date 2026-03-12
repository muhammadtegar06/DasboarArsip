<?php
session_start();
require_once "../../config/database.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_pengajuan = isset($_POST['id_pengajuan']) ? (int) $_POST['id_pengajuan'] : 0;
    // Nilai ini sekarang murni "Siap Kirim", "To Send", atau "Cancel"
    $status_baru = isset($_POST['status_baru']) ? mysqli_real_escape_string($mysqli, $_POST['status_baru']) : '';
    $waktu_sekarang = date('Y-m-d H:i:s');

    if ($id_pengajuan > 0 && !empty($status_baru)) {

        // Memulai transaksi agar jika satu tabel gagal diupdate, semuanya batal otomatis
        mysqli_begin_transaction($mysqli);

        try {
            // 1. UPDATE STATUS DI TABEL PENGAJUAN
            $q_update_pengajuan = "UPDATE tbl_pengajuan SET status = '$status_baru' WHERE id = '$id_pengajuan'";
            if (!mysqli_query($mysqli, $q_update_pengajuan)) {
                throw new Exception("Gagal update tabel pengajuan.");
            }

            // 2. CEK & UPDATE/INSERT KE TABEL PENGIRIMAN
            $id_pengiriman = 0;
            $q_cek = mysqli_query($mysqli, "SELECT id FROM tbl_pengiriman WHERE id_pengajuan = '$id_pengajuan'");

            if (mysqli_num_rows($q_cek) > 0) {
                // Jika sudah punya data pengiriman
                $row = mysqli_fetch_assoc($q_cek);
                $id_pengiriman = $row['id'];

                $q_update_kirim = "UPDATE tbl_pengiriman SET status_pengiriman = '$status_baru' WHERE id = '$id_pengiriman'";
                if (!mysqli_query($mysqli, $q_update_kirim)) {
                    throw new Exception("Gagal update tabel pengiriman.");
                }
            } else {
                // Jika baru pertama kali diubah dari Disetujui
                $q_insert_kirim = "INSERT INTO tbl_pengiriman (id_pengajuan, tanggal_pengiriman, status_pengiriman) 
                                   VALUES ('$id_pengajuan', '$waktu_sekarang', '$status_baru')";
                if (!mysqli_query($mysqli, $q_insert_kirim)) {
                    throw new Exception("Gagal insert tabel pengiriman.");
                }
                $id_pengiriman = mysqli_insert_id($mysqli);
            }

            // 3. INSERT RIWAYAT KE TABEL HISTORY PENGIRIMAN
            $keterangan = "Status diubah menjadi " . $status_baru;
            $q_history = "INSERT INTO tbl_history_pengiriman (id_pengiriman, waktu, status, keterangan) 
                          VALUES ('$id_pengiriman', '$waktu_sekarang', '$status_baru', '$keterangan')";

            if (!mysqli_query($mysqli, $q_history)) {
                throw new Exception("Gagal insert riwayat pengiriman.");
            }

            // Jika semua langkah sukses, Eksekusi final
            mysqli_commit($mysqli);
            echo json_encode(['status' => 'success', 'message' => 'Status pengiriman berhasil diubah.']);

        } catch (Exception $e) {
            // Rollback jika ada yang error
            mysqli_rollback($mysqli);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }

    } else {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak valid atau kosong.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Request']);
}
?>