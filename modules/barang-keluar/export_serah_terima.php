<?php
require_once "../../config/database.php";

$id_pengajuan = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Ambil data header
$q_head = mysqli_query($mysqli, "SELECT p.*, d.nama_divisi, d.singkatan_divisi 
                                FROM tbl_pengajuan p 
                                JOIN tbl_divisi d ON p.id_divisi = d.id 
                                WHERE p.id = '$id_pengajuan'");
$head = mysqli_fetch_assoc($q_head);

if (!$head)
    die("Data tidak ditemukan!");

// Hitung total bantex
$q_count = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT COUNT(*) as total FROM tbl_bantex b JOIN tbl_box bx ON b.id_box = bx.id WHERE bx.id_pengajuan = '$id_pengajuan'"));
$total_bantex = $q_count['total'];

// Nama file excel
$filename = "Serah_Terima_Arsip_" . $head['singkatan_divisi'] . "_" . date('Ymd') . ".xls";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
?>

<style>
    .text-center {
        text-align: center;
    }

    .font-bold {
        font-weight: bold;
    }

    .border {
        border: 1px solid #000;
    }
</style>

<table border="0">
    <tr>
        <td colspan="10" class="text-center font-bold" style="font-size: 14px;">Serah terima Box dan Bantex</td>
    </tr>
    <tr>
        <td colspan="10" class="text-center">Pada hari,
            <?= date('l, d F Y') ?>
        </td>
    </tr>
    <tr>
        <td colspan="10"></td>
    </tr>
    <tr>
        <td colspan="10">Telah Di Serahkan Box dan Bantex dalam Jumlah :</td>
    </tr>
    <tr>
        <td colspan="2" class="border font-bold">KETERANGAN</td>
        <td colspan="2" class="border font-bold">JUMLAH</td>
        <td colspan="6"></td>
    </tr>
    <tr>
        <td colspan="2" class="border text-center">BOX</td>
        <td colspan="2" class="border text-center">
            <?= $head['jumlah_box'] ?>
        </td>
        <td colspan="6"></td>
    </tr>
    <tr>
        <td colspan="2" class="border text-center">BANTEK</td>
        <td colspan="2" class="border text-center">
            <?= $total_bantex ?>
        </td>
        <td colspan="6"></td>
    </tr>

    <tr>
        <td colspan="10"></td>
    </tr>
    <tr>
        <td colspan="3" class="text-center font-bold">YANG MEMBERIKAN</td>
        <td colspan="4"></td>
        <td colspan="3" class="text-center font-bold">YANG MENERIMA</td>
    </tr>
    <tr>
        <td colspan="3" class="text-center">PIHAK PERTAMA</td>
        <td colspan="4"></td>
        <td colspan="3" class="text-center">PIHAK KEDUA</td>
    </tr>
    <tr>
        <td colspan="10" style="height: 50px;"></td>
    </tr>
    <tr>
        <td colspan="3" class="text-center">...................</td>
        <td colspan="4"></td>
        <td colspan="3" class="text-center">...................</td>
    </tr>
    <tr>
        <td colspan="10"></td>
    </tr>

    <tr bgcolor="#f2f2f2">
        <th class="border">NAMA DIVISI HEAD OFFICE</th>
        <th class="border">Box Ke -</th>
        <th class="border">RF-ID</th>
        <th class="border">BANTEK Ke - 1</th>
        <th class="border">BANTEK Ke - 2</th>
        <th class="border">BANTEK Ke - 3</th>
        <th class="border">BANTEK Ke - 4</th>
        <th class="border">BANTEK Ke - 5</th>
        <th class="border">BANTEK Ke - 6</th>
        <th class="border">Lokasi Rak</th>
    </tr>

    <?php
    $q_box = mysqli_query($mysqli, "SELECT * FROM tbl_box WHERE id_pengajuan = '$id_pengajuan' ORDER BY id ASC");
    $no_box = 1;
    while ($box = mysqli_fetch_assoc($q_box)) {
        $id_box = $box['id'];

        // Ambil label bantex untuk box ini (asumsi 6 bantex per baris sesuai template excel Anda)
        $bantex_labels = [];
        $q_btx = mysqli_query($mysqli, "SELECT label_judul FROM tbl_bantex WHERE id_box = '$id_box' ORDER BY id ASC LIMIT 6");
        while ($btx = mysqli_fetch_assoc($q_btx)) {
            $bantex_labels[] = $btx['label_judul'];
        }
        ?>
        <tr>
            <td class="border">
                <?= $head['singkatan_divisi'] . " - " . $head['nama_divisi'] ?>
            </td>
            <td class="border text-center">
                <?= $no_box++ ?>
            </td>
            <td class="border">
                <?= $box['rfid_code'] ?>
            </td>
            <?php for ($i = 0; $i < 6; $i++): ?>
                <td class="border">
                    <?= isset($bantex_labels[$i]) ? $bantex_labels[$i] : '-' ?>
                </td>
            <?php endfor; ?>
            <td class="border">
                <?= $box['lokasi_arsip'] ?>
            </td>
        </tr>
    <?php } ?>
</table>