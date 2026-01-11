<?php
session_start();
// Sesuaikan path ini dengan struktur folder Anda
// Asumsi: file ini ada di folder modules/barang-masuk/
require_once "../../config/database.php";

header('Content-Type: application/json');

// Cek apakah request method POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Request']);
    exit;
}

// Ambil data dari AJAX
$kode_divisi = mysqli_real_escape_string($mysqli, $_POST['divisi']); // Misal: "DTPI"
$lokasi = mysqli_real_escape_string($mysqli, $_POST['lokasi']);
$jumlah_box = (int) $_POST['jumlah_box'];
$tanggal = date('Y-m-d');

// Mulai Transaksi Database (Penting agar data konsisten)
mysqli_begin_transaction($mysqli);

try {
    // 1. CARI ID DIVISI BERDASARKAN SINGKATAN (KODE)
    // Karena value di form adalah singkatan (misal: "DTPI"), kita butuh ID aslinya (misal: 1)
    $query_div = mysqli_query($mysqli, "SELECT id FROM tbl_divisi WHERE singkatan_divisi = '$kode_divisi'");
    $data_div = mysqli_fetch_assoc($query_div);

    if (!$data_div) {
        throw new Exception("Data Divisi tidak ditemukan di database.");
    }
    $id_divisi = $data_div['id'];


    // 2. INSERT KE TABEL PENGAJUAN (HEADER)
    $q_pengajuan = "INSERT INTO tbl_pengajuan (id_divisi, tanggal_pengajuan, status) 
                    VALUES ('$id_divisi', '$tanggal', 'Pending')";

    if (!mysqli_query($mysqli, $q_pengajuan)) {
        throw new Exception("Gagal membuat data pengajuan: " . mysqli_error($mysqli));
    }

    // Ambil ID Pengajuan yang baru saja dibuat
    $id_pengajuan = mysqli_insert_id($mysqli);


    // 3. LOOPING INSERT BOX
    for ($i = 1; $i <= $jumlah_box; $i++) {
        // Generate nama dummy box, misal "Box 1", "Box 2"
        $ket_box = "Box ke-" . $i;

        $q_box = "INSERT INTO tbl_box (id_pengajuan, id_divisi, lokasi_arsip, keterangan_box, tanggal_pengajuan, status_acc) 
                  VALUES ('$id_pengajuan', '$id_divisi', '$lokasi', '$ket_box', '$tanggal', 'Menunggu')";

        if (!mysqli_query($mysqli, $q_box)) {
            throw new Exception("Gagal menyimpan Box ke-$i");
        }

        // Ambil ID Box yang baru saja dibuat
        $id_box = mysqli_insert_id($mysqli);

        // 4. LOOPING INSERT BANTEX (1 BOX = 6 BANTEX)
        for ($b = 1; $b <= 6; $b++) {
            $nama_bantex = "Bantex " . $b; // Default nama

            $q_bantex = "INSERT INTO tbl_bantex (id_box, nama_bantex, keterangan) 
                         VALUES ('$id_box', '$nama_bantex', 'Kosong')";

            if (!mysqli_query($mysqli, $q_bantex)) {
                throw new Exception("Gagal menyimpan Bantex untuk Box ID $id_box");
            }
        }
    }

    // Jika semua lancar, simpan permanen (Commit)
    mysqli_commit($mysqli);

    echo json_encode([
        'status' => 'success',
        'message' => 'Data berhasil disimpan. ' . $jumlah_box . ' Box dan isinya telah dibuat.'
    ]);

} catch (Exception $e) {
    // Jika ada error, batalkan semua perubahan (Rollback)
    mysqli_rollback($mysqli);

    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>