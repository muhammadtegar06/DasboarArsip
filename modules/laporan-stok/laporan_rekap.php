<?php
// laporan_rekap.php
// Tampilan Laporan dengan Merged Cells & Export

// --- 1. DATA DUMMY (Sesuai Gambar) ---
// Kita strukturkan data agar mudah di-looping
$data_laporan = [
    [
        'divisi' => 'DHPU',
        'no_kotak' => 'xxx-xxx-xx',
        'asal_arsip' => 'Head Office PTPN IV',
        'jml_bantex' => 1,
        'jml_box' => 1,
        'dokumen' => [
            ['no_urut' => 1, 'nama' => 'Surat Perjanjian Sewa Gedung Lt.8 No. ...', 'periode' => 'V/2023', 'ket' => '-'],
            ['no_urut' => 2, 'nama' => 'Surat Perjanjian Sewa Gedung Lt.9 No. ...', 'periode' => 'I/2025', 'ket' => '-'],
            ['no_urut' => 3, 'nama' => 'Surat Perjanjian Sewa Kendaraan KIA EV 9', 'periode' => 'II/2024', 'ket' => '-'],
            ['no_urut' => 4, 'nama' => 'Surat Perjanjian Sewa Kendaraan Zenix V', 'periode' => 'III/2024', 'ket' => '-'],
            ['no_urut' => 5, 'nama' => 'S.Perj Renovasi Lt.8', 'periode' => 'IV/2025', 'ket' => '-'],
        ]
    ],
    // Contoh Data Kedua (Untuk Tes)
    [
        'divisi' => 'DTI',
        'no_kotak' => 'DTI-001-A',
        'asal_arsip' => 'Gudang Arsip',
        'jml_bantex' => 2,
        'jml_box' => 1,
        'dokumen' => [
            ['no_urut' => 1, 'nama' => 'Kontrak Maintenance Server', 'periode' => '2024', 'ket' => 'Penting'],
            ['no_urut' => 2, 'nama' => 'Berita Acara Serah Terima Laptop', 'periode' => '2024', 'ket' => '-'],
        ]
    ]
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekapitulasi Arsip</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    
    <style>
        /* Styling Khusus agar mirip Gambar */
        body { background: #f5f5f5; padding: 20px; font-family: Arial, sans-serif; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        
        /* Tabel Style */
        .table-rekap { width: 100%; border-collapse: collapse; background: white; font-size: 12px; }
        .table-rekap th, .table-rekap td { border: 1px solid #000; padding: 8px; vertical-align: middle; }
        
        /* Header Biru Gelap Sesuai Gambar */
        .table-rekap thead th { 
            background-color: #1f4e78; /* Biru Tua */
            color: #ffc000; /* Kuning Emas */
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        /* Kolom Nomor Biru */
        .col-no { background-color: #1f4e78; color: white; text-align: center; font-weight: bold; }

        /* Media Print (Untuk PDF) */
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; background: white; }
            .card { border: none; box-shadow: none; }
            .table-rekap th { background-color: #1f4e78 !important; color: #ffc000 !important; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Laporan Rekapitulasi Dokumen</h6>
            <div class="no-print">
                <button onclick="exportTableToExcel('tabelRekap', 'Laporan_Rekap_Arsip')" class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel mr-1"></i> Export Excel
                </button>
                <button onclick="window.print()" class="btn btn-danger btn-sm ml-2">
                    <i class="fas fa-file-pdf mr-1"></i> Export PDF / Print
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table-rekap" id="tabelRekap">
                    <thead>
                        <tr>
                            <th width="3%">NO</th>
                            <th width="10%">DIVISI</th>
                            <th width="12%">NOMOR KOTAK<br>INDOARSIP / RFID</th>
                            <th width="12%">ASAL ARSIP</th>
                            <th width="5%">NOMOR URUT<br>DOKUMEN</th>
                            <th width="25%">NAMA DOKUMEN</th>
                            <th width="8%">PERIODE DOKUMEN</th>
                            <th width="10%">KETERANGAN DOKUMEN</th>
                            <th width="8%">JUMLAH BANTEX</th>
                            <th width="7%">JUMLAH BOX</th>
                        </tr>
                        <tr>
                            <th>(a)</th><th>(b)</th><th>(c)</th><th>(d)</th><th>(e)</th><th>(f)</th><th>(g)</th><th>(h)</th><th>(i)</th><th>(j)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no_utama = 1;
                        foreach($data_laporan as $row) { 
                            // Hitung berapa baris dokumen dalam 1 box (untuk rowspan)
                            $rowspan = count($row['dokumen']);
                            $first = true; // Penanda baris pertama dalam grup

                            foreach($row['dokumen'] as $doc) {
                        ?>
                            <tr>
                                <?php if($first): ?>
                                    <td class="text-center" rowspan="<?php echo $rowspan; ?>"><?php echo $no_utama++; ?></td>
                                    <td class="text-center" rowspan="<?php echo $rowspan; ?>"><?php echo $row['divisi']; ?></td>
                                    <td class="text-center" rowspan="<?php echo $rowspan; ?>"><?php echo $row['no_kotak']; ?></td>
                                    <td class="text-center" rowspan="<?php echo $rowspan; ?>"><?php echo $row['asal_arsip']; ?></td>
                                <?php endif; ?>

                                <td class="text-center"><?php echo $doc['no_urut']; ?></td>
                                <td><?php echo $doc['nama']; ?></td>
                                <td class="text-center"><?php echo $doc['periode']; ?></td>
                                <td class="text-center"><?php echo $doc['ket']; ?></td>

                                <?php if($first): ?>
                                    <td class="text-center" rowspan="<?php echo $rowspan; ?>"><?php echo $row['jml_bantex']; ?></td>
                                    <td class="text-center" rowspan="<?php echo $rowspan; ?>"><?php echo $row['jml_box']; ?></td>
                                <?php endif; ?>
                            </tr>
                        <?php 
                                $first = false; // Set false agar baris berikutnya tidak mencetak kolom merged
                            } // End foreach dokumen
                        } // End foreach box
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function exportTableToExcel(tableID, filename = ''){
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    
    // Tambahkan header khusus agar Excel membaca border & warna
    var header = '<html xmlns:o="urn:schemas-microsoft-com:office:office" ' +
                 'xmlns:x="urn:schemas-microsoft-com:office:excel" ' +
                 'xmlns="http://www.w3.org/TR/REC-html40"><head>' +
                 '' +
                 '<style>table, th, td {border: 1px solid black;}</style>' + 
                 '</head><body>';
    
    var footer = '</body></html>';
    var finalHTML = header + tableSelect.outerHTML + footer;

    // Create Download Link
    filename = filename?filename+'.xls':'excel_data.xls';
    downloadLink = document.createElement("a");
    
    document.body.appendChild(downloadLink);
    
    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['\ufeff', finalHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob( blob, filename);
    }else{
        downloadLink.href = 'data:' + dataType + ', ' + encodeURIComponent(finalHTML);
        downloadLink.download = filename;
        downloadLink.click();
    }
}
</script>

</body>
</html>