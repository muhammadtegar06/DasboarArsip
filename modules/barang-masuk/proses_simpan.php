<?php
session_start();
require_once "../../config/database.php";

header('Content-Type: application/json');

// Cek Metode Request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Request']);
    exit;
}

// 1. Tangkap Data Form
$kode_divisi = mysqli_real_escape_string($mysqli, $_POST['divisi']); // "DTPI"
$lokasi = mysqli_real_escape_string($mysqli, $_POST['lokasi']); // "Head Office (HO)"
$jumlah_box = (int) $_POST['jumlah_box'];
$tanggal = date('Y-m-d');

// Mulai Transaksi Database
mysqli_begin_transaction($mysqli);

try {
    // -----------------------------------------------------------------
    // LANGKAH 1: GENERATE ID TRANSAKSI (TRX-TAHUN-URUT)
    // -----------------------------------------------------------------
    $tahun = date('Y');
    // Cari nomor terakhir di tahun ini
    $cek_no = mysqli_query($mysqli, "SELECT no_pengajuan FROM tbl_pengajuan WHERE no_pengajuan LIKE 'TRX-$tahun-%' ORDER BY id DESC LIMIT 1");
    $data_no = mysqli_fetch_assoc($cek_no);

    if ($data_no) {
        // Jika ada (misal TRX-2026-005), ambil angka terakhir (5) tambah 1
        $urutan_terakhir = (int) substr($data_no['no_pengajuan'], -3);
        $urutan_baru = $urutan_terakhir + 1;
    } else {
        // Jika belum ada, mulai dari 1
        $urutan_baru = 1;
    }
    // Format: TRX-2026-001
    $no_pengajuan = 'TRX-' . $tahun . '-' . sprintf("%03d", $urutan_baru);


    // -----------------------------------------------------------------
    // LANGKAH 2: PROSES UPLOAD FILE SURAT
    // -----------------------------------------------------------------
    $file_surat_name = null;

    // Cek apakah ada file yang diupload dan tidak error
    if (isset($_FILES['file_surat']) && $_FILES['file_surat']['error'] == 0) {
        $target_dir = "../../uploads/surat/";

        // Buat folder jika belum ada
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Ambil ekstensi file (pdf/jpg)
        $ext = pathinfo($_FILES['file_surat']['name'], PATHINFO_EXTENSION);

        // Nama file baru disesuaikan dengan ID Transaksi agar rapi (TRX_2026_001.pdf)
        $new_name = str_replace(['-', ' '], '_', $no_pengajuan) . '.' . $ext;
        $target_file = $target_dir . $new_name;

        // Pindahkan file dari folder sementara ke folder tujuan
        if (move_uploaded_file($_FILES['file_surat']['tmp_name'], $target_file)) {
            $file_surat_name = $new_name;
        } else {
            throw new Exception("Gagal mengupload file surat. Cek permission folder.");
        }
    }


    // -----------------------------------------------------------------
    // LANGKAH 3: CARI ID DIVISI
    // -----------------------------------------------------------------
    $q_div = mysqli_query($mysqli, "SELECT id FROM tbl_divisi WHERE singkatan_divisi = '$kode_divisi'");
    $d_div = mysqli_fetch_assoc($q_div);

    if (!$d_div) {
        throw new Exception("Divisi '$kode_divisi' tidak ditemukan di database.");
    }
    $id_divisi = $d_div['id'];


    // -----------------------------------------------------------------
    // LANGKAH 4: INSERT HEADER PENGAJUAN
    // -----------------------------------------------------------------
    $q_header = "INSERT INTO tbl_pengajuan 
                 (no_pengajuan, id_divisi, tanggal_pengajuan, jumlah_box, file_surat, status) 
                 VALUES 
                 ('$no_pengajuan', '$id_divisi', '$tanggal', '$jumlah_box', '$file_surat_name', 'Pending')";

    if (!mysqli_query($mysqli, $q_header)) {
        throw new Exception("Gagal simpan Header Pengajuan: " . mysqli_error($mysqli));
    }

    // Ambil ID Auto Increment yang baru saja terbentuk
    $id_pengajuan = mysqli_insert_id($mysqli);


    // -----------------------------------------------------------------
    // LANGKAH 5: GENERATE BOX & BANTEX KOSONG
    // -----------------------------------------------------------------
    for ($i = 1; $i <= $jumlah_box; $i++) {

        // Insert Box (Detail Level 1)
        // Keterangan box dihapus karena kode box nanti diisi saat ACC (ref_id/kode_box)
        $q_box = "INSERT INTO tbl_box (id_pengajuan, lokasi_arsip, status_acc) 
                  VALUES ('$id_pengajuan', '$lokasi', 'Pending')";

        if (!mysqli_query($mysqli, $q_box)) {
            throw new Exception("Gagal generate Box ke-$i");
        }
        $id_box = mysqli_insert_id($mysqli);

        // Insert 6 Bantex Default per Box (Detail Level 2)
        for ($b = 1; $b <= 6; $b++) {
            $nama_bantex = "Bantex " . $b;
            $q_bantex = "INSERT INTO tbl_bantex (id_box, nama_bantex) 
                         VALUES ('$id_box', '$nama_bantex')";

            if (!mysqli_query($mysqli, $q_bantex)) {
                throw new Exception("Gagal generate Bantex");
            }
        }
    }

    // Jika semua proses sukses, simpan permanen
    mysqli_commit($mysqli);

    echo json_encode([
        'status' => 'success',
        'message' => 'Pengajuan Berhasil! ID Transaksi: ' . $no_pengajuan
    ]);

} catch (Exception $e) {
    // Jika ada error, batalkan semua perubahan database
    mysqli_rollback($mysqli);

    // Hapus file yang terlanjur diupload jika database gagal
    if ($file_surat_name && file_exists("../../uploads/surat/" . $file_surat_name)) {
        unlink("../../uploads/surat/" . $file_surat_name);
    }

    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>