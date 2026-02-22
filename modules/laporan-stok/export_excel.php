<?php
session_start();
// Sesuaikan dengan path database Anda (Biasanya naik 2 atau 3 level)
require_once "../../config/database.php";

$selected_divisi = isset($_GET['filter_divisi']) ? mysqli_real_escape_string($mysqli, $_GET['filter_divisi']) : '';

// Filter data yang tampil di Laporan
$where = "WHERE p.status IN ('Disetujui', 'Siap Kirim', 'Telah Dikirim', 'Serah Terima')";
if ($selected_divisi != '') {
	$where .= " AND d.singkatan_divisi = '$selected_divisi'";
}

// 1. Hitung Total Keseluruhan
$q_totals = mysqli_query($mysqli, "
    SELECT 
        COUNT(DISTINCT bx.id) as total_box,
        COUNT(b.id) as total_bantex
    FROM tbl_box bx
    JOIN tbl_pengajuan p ON bx.id_pengajuan = p.id
    JOIN tbl_divisi d ON p.id_divisi = d.id
    LEFT JOIN tbl_bantex b ON b.id_box = bx.id
    $where
");
$totals = mysqli_fetch_assoc($q_totals);
$total_box = $totals['total_box'] ?? 0;
$total_bantex = $totals['total_bantex'] ?? 0;

// 2. Format Tanggal Bahasa Indonesia
$hari_array = array('Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jum\'at', 'Saturday' => 'Sabtu');
$bulan_array = array(1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember');
$hari_ini_indo = "Pada hari, " . $hari_array[date('l')] . " " . date('d') . " " . $bulan_array[(int) date('m')] . " " . date('Y');

// 3. Set Header Untuk Download File Excel (.xls)
$nama_divisi_file = $selected_divisi != '' ? $selected_divisi : 'ALL_DIVISI';
$filename = "REKAP_BANTEK_" . $nama_divisi_file . "_" . date('d_M_Y') . ".xls";

header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<style>
		.teks-normal {
			font-family: Arial, sans-serif;
			font-size: 13px;
		}

		.tabel-data {
			font-family: Arial, sans-serif;
			border-collapse: collapse;
			width: 100%;
		}

		.tabel-data th,
		.tabel-data td {
			border: 1px solid #000000;
			padding: 6px;
		}

		.tabel-data th {
			background-color: #f2f2f2;
			font-weight: bold;
			text-align: center;
			vertical-align: middle;
		}
	</style>
</head>

<body>
	<table border="0" class="teks-normal">
		<tr>
			<td colspan="9"></td>
		</tr>
		<tr>
			<td colspan="9"></td>
		</tr>
		<tr>
			<td colspan="3"></td>
			<td colspan="6"><b>Serah terima Box dan Bantek</b></td>
		</tr>
		<tr>
			<td colspan="3"></td>
			<td colspan="6"><?= $hari_ini_indo ?></td>
		</tr>
		<tr>
			<td colspan="3"></td>
			<td colspan="6">Telah Di Serahkan Box dan Bantek dalam Jumlah :</td>
		</tr>
		<tr>
			<td colspan="3"></td>
			<td><b>KETERANGAN</b></td>
			<td><b>JUMLAH</b></td>
			<td colspan="4"></td>
		</tr>
		<tr>
			<td colspan="3"></td>
			<td>BOX</td>
			<td align="left"><?= $total_box ?></td>
			<td colspan="4"></td>
		</tr>
		<tr>
			<td colspan="3"></td>
			<td>BANTEK</td>
			<td align="left"><?= $total_bantex ?></td>
			<td colspan="4"></td>
		</tr>
		<tr>
			<td colspan="9"></td>
		</tr>
		<tr>
			<td colspan="3"></td>
			<td align="center"><b>YANG MEMBERIKAN</b></td>
			<td></td>
			<td align="center"><b>YANG MENERIMA</b></td>
			<td colspan="3"></td>
		</tr>
		<tr>
			<td colspan="3"></td>
			<td align="center"><b>PIHAK PERTAMA</b></td>
			<td></td>
			<td align="center"><b>PIHAK KEDUA</b></td>
			<td colspan="3"></td>
		</tr>
		<tr>
			<td colspan="9"></td>
		</tr>
		<tr>
			<td colspan="9"></td>
		</tr>
		<tr>
			<td colspan="3"></td>
			<td align="center">.............................</td>
			<td></td>
			<td align="center">.............................</td>
			<td colspan="3"></td>
		</tr>
		<tr>
			<td colspan="9"></td>
		</tr>
	</table>

	<table class="tabel-data">
		<thead>
			<tr>
				<th style="width: 350px;">NAMA DIVISI HEAD OFFICE</th>
				<th style="width: 150px;">Pengajuan Box Ke -</th>
				<th style="width: 150px;">RF-ID</th>
				<th style="width: 250px;">BANTEK Ke - 1</th>
				<th style="width: 250px;">BANTEK Ke - 2</th>
				<th style="width: 250px;">BANTEK Ke - 3</th>
				<th style="width: 250px;">BANTEK Ke - 4</th>
				<th style="width: 250px;">BANTEK Ke - 5</th>
				<th style="width: 250px;">BANTEK Ke - 6</th>
			</tr>
		</thead>
		<tbody>
			<?php
			// Looping Data Tabel Box & Bantex
			$q_box = mysqli_query($mysqli, "
                SELECT bx.id as id_box, bx.rfid_code, d.nama_divisi, d.singkatan_divisi
                FROM tbl_box bx
                JOIN tbl_pengajuan p ON bx.id_pengajuan = p.id
                JOIN tbl_divisi d ON p.id_divisi = d.id
                $where
                ORDER BY d.nama_divisi ASC, bx.id ASC
            ");

			$current_divisi = '';
			$box_counter = 1;

			while ($row = mysqli_fetch_assoc($q_box)) {
				// Restart counter box jika divisinya ganti
				if ($current_divisi != $row['singkatan_divisi']) {
					$current_divisi = $row['singkatan_divisi'];
					$box_counter = 1;
				}

				$nama_divisi_full = $row['singkatan_divisi'] . ' - ' . $row['nama_divisi'];

				// Ambil label bantex di dalam box ini (Maksimal 6)
				$q_bantex = mysqli_query($mysqli, "SELECT label_judul FROM tbl_bantex WHERE id_box = '{$row['id_box']}' ORDER BY id ASC LIMIT 6");

				$bantex_labels = array_fill(0, 6, ''); // Siapkan 6 cell kosong
			
				$i = 0;
				while ($b = mysqli_fetch_assoc($q_bantex)) {
					// Gunakan htmlspecialchars agar text yang punya karakter khusus seperti '&' tetap aman di Excel
					$bantex_labels[$i] = htmlspecialchars($b['label_judul']);
					$i++;
				}
				?>
				<tr>
					<td><?= htmlspecialchars($nama_divisi_full) ?></td>
					<td align="center"><?= $box_counter ?></td>
					<td align="center" style="mso-number-format:'\@';"><?= htmlspecialchars($row['rfid_code']) ?: '-' ?>
					</td>
					<td><?= $bantex_labels[0] ?></td>
					<td><?= $bantex_labels[1] ?></td>
					<td><?= $bantex_labels[2] ?></td>
					<td><?= $bantex_labels[3] ?></td>
					<td><?= $bantex_labels[4] ?></td>
					<td><?= $bantex_labels[5] ?></td>
				</tr>
				<?php
				$box_counter++;
			}
			?>
		</tbody>
	</table>
</body>

</html>