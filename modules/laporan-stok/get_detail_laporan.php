<?php
session_start();
require_once "../../config/database.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$q = mysqli_query($mysqli, "
    SELECT p.*, d.nama_divisi, d.singkatan_divisi 
    FROM tbl_pengajuan p 
    JOIN tbl_divisi d ON p.id_divisi = d.id 
    WHERE p.id = '$id'
");
$row = mysqli_fetch_assoc($q);

if (!$row)
    exit;

// 1. Ambil History
$history = [['status' => 'Pengajuan Disetujui', 'date' => date('d M Y H:i', strtotime($row['tanggal_pengajuan'])), 'user' => 'Sistem', 'color' => 'info']];
$q_hist = mysqli_query($mysqli, "SELECT h.waktu, h.status, h.keterangan FROM tbl_history_pengiriman h JOIN tbl_pengiriman pg ON h.id_pengiriman = pg.id WHERE pg.id_pengajuan = '$id' ORDER BY h.waktu ASC");
while ($h = mysqli_fetch_assoc($q_hist)) {
    $st = strtolower($h['status']);
    $color = (strpos($st, 'kirim') !== false || strpos($st, 'terkirim') !== false) ? 'success' : 'primary';
    $history[] = ['status' => strtoupper($h['status']), 'date' => date('d M Y H:i', strtotime($h['waktu'])), 'user' => 'Logistik', 'color' => $color];
}

// 2. Ambil Box, Bantex, Dokumen
$boxes = [];
$q_box = mysqli_query($mysqli, "SELECT * FROM tbl_box WHERE id_pengajuan = '$id'");
while ($bx = mysqli_fetch_assoc($q_box)) {
    $id_bx = $bx['id'];
    $bantexes = [];
    $q_bt = mysqli_query($mysqli, "SELECT * FROM tbl_bantex WHERE id_box = '$id_bx'");
    while ($bt = mysqli_fetch_assoc($q_bt)) {
        $id_bt = $bt['id'];
        $docs = [];
        $q_dc = mysqli_query($mysqli, "SELECT * FROM tbl_dokumen WHERE id_bantex = '$id_bt'");
        while ($dc = mysqli_fetch_assoc($q_dc))
            $docs[] = $dc;
        $bt['dokumen'] = $docs;
        $bantexes[] = $bt;
    }
    $bx['bantex'] = $bantexes;
    $boxes[] = $bx;
}

echo json_encode([
    'id_transaksi' => $row['no_pengajuan'],
    'divisi' => $row['nama_divisi'],
    'jml_box' => $row['jumlah_box'],
    'jml_bantex' => count($bantexes), // Estimasi
    'history' => $history,
    'boxes' => $boxes
]);