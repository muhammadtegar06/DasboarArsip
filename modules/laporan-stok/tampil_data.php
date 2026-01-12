<?php
// Tampil Data: ELEGANT LANDING PAGE (Big Search, No Actions)
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('location: 404.html');
} else {
    // --- 1. DATA DUMMY ---
    $data_laporan = [
        [
            'kode_divisi' => 'DSPN',
            'divisi_nama' => 'Sekretariat Perusahaan', 
            'no_kotak'    => 'DSPN-001',
            'jml_bantex'  => 2,
            'jml_box'     => 1,
            'dokumen'     => [
                ['nama' => 'SK Direksi Pengangkatan Pejabat', 'tahun' => '2024'],
                ['nama' => 'Risalah Rapat Rencana Kerja', 'tahun' => '2024'],
            ]
        ],
        [
            'kode_divisi' => 'DTIS',
            'divisi_nama' => 'Teknologi Informasi',
            'no_kotak'    => 'DTIS-005-A',
            'jml_bantex'  => 5,
            'jml_box'     => 1,
            'dokumen'     => [
                ['nama' => 'Kontrak Maintenance Jaringan FO', 'tahun' => '2025'],
                ['nama' => 'Topologi Network HO', 'tahun' => '2024'],
                ['nama' => 'Lisensi Software Microsoft', 'tahun' => '2024'],
            ]
        ],
        [
            'kode_divisi' => 'DHPU',
            'divisi_nama' => 'HPS & Umum',
            'no_kotak'    => 'DHPU-102',
            'jml_bantex'  => 3,
            'jml_box'     => 2,
            'dokumen'     => [
                ['nama' => 'Perjanjian Sewa Gedung Lt.8', 'tahun' => '2023'],
            ]
        ]
    ];

    // --- 2. LOGIKA FILTER ---
    $selected_divisi = isset($_GET['filter_divisi']) ? $_GET['filter_divisi'] : '';
    $data_tampil = [];
    
    // Hitung statistik sederhana untuk pemanis
    $total_dokumen = 0;
    $total_box = 0;

    if($selected_divisi != '') {
        foreach($data_laporan as $row) {
            if($row['kode_divisi'] == $selected_divisi) {
                $data_tampil[] = $row;
                $total_dokumen += count($row['dokumen']);
                $total_box += $row['jml_box'];
            }
        }
    } else {
        $data_tampil = $data_laporan;
        foreach($data_laporan as $r) {
            $total_dokumen += count($r['dokumen']);
            $total_box += $r['jml_box'];
        }
    }
?>

    <style>
        /* --- STYLE ELEGANT --- */
        :root {
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); /* Indigo Theme */
            --bg-body: #f3f4f6;
            --text-dark: #1f2937;
            --text-muted: #6b7280;
        }

        body { background-color: var(--bg-body); font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; }

        /* 1. HERO SECTION (Big Search Background) */
        .hero-section {
            background: var(--primary-gradient);
            padding: 60px 20px 80px; /* Padding bawah besar untuk overlap */
            border-radius: 0 0 30px 30px;
            color: white;
            text-align: center;
            position: relative;
            margin-bottom: -50px; /* Overlap effect */
        }
        .hero-title { font-size: 2.5rem; font-weight: 800; margin-bottom: 10px; letter-spacing: -0.5px; }
        .hero-subtitle { font-size: 1.1rem; opacity: 0.9; font-weight: 300; max-width: 600px; margin: 0 auto; }

        /* 2. FLOATING SEARCH CARD */
        .search-card-container {
            max-width: 800px;
            margin: 0 auto 40px auto;
            position: relative;
            z-index: 10;
        }
        .search-card {
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        /* Input Besar */
        .form-select-lg-custom {
            flex-grow: 1;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 15px 20px;
            font-size: 1.1rem;
            color: var(--text-dark);
            background-color: #f9fafb;
            cursor: pointer;
            transition: all 0.3s;
        }
        .form-select-lg-custom:hover, .form-select-lg-custom:focus {
            border-color: #4f46e5;
            background-color: white;
            outline: none;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .btn-search-lg {
            background-color: #111827; /* Hitam Elegant */
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
            display: flex; align-items: center;
        }
        .btn-search-lg:hover { transform: translateY(-2px); background-color: #000; }

        /* 3. CONTENT AREA */
        .content-wrapper { max-width: 1100px; margin: 0 auto; padding: 0 20px 50px; }

        /* Toolbar (Export/Print) */
        .toolbar-clean {
            display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;
        }
        .stat-badge {
            background: white; padding: 8px 16px; border-radius: 50px;
            font-size: 0.9rem; font-weight: 600; color: var(--text-dark);
            box-shadow: 0 2px 5px rgba(0,0,0,0.05); margin-right: 10px;
        }
        .stat-badge i { color: #4f46e5; margin-right: 5px; }

        /* TABLE DESIGN */
        .card-table {
            background: white; border-radius: 16px; overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid #f3f4f6;
        }
        .table-elegant { width: 100%; border-collapse: collapse; }
        .table-elegant thead { background-color: #f9fafb; border-bottom: 2px solid #f3f4f6; }
        .table-elegant th {
            padding: 20px; text-align: left; font-size: 0.85rem; text-transform: uppercase;
            letter-spacing: 1px; color: var(--text-muted); font-weight: 700;
        }
        .table-elegant td {
            padding: 20px; border-bottom: 1px solid #f3f4f6;
            font-size: 0.95rem; color: var(--text-dark); vertical-align: middle;
        }
        .table-elegant tr:last-child td { border-bottom: none; }
        .table-elegant tr:hover { background-color: #fcfcfc; }

        /* Typography di Tabel */
        .cell-title { font-weight: 600; display: block; color: #111827; }
        .cell-subtitle { font-size: 0.85rem; color: #9ca3af; margin-top: 3px; display: block; }
        .rfid-tag {
            background: #eef2ff; color: #4f46e5; padding: 5px 10px;
            border-radius: 6px; font-family: monospace; font-size: 0.9rem; letter-spacing: 0.5px;
        }
        .number-circle {
            width: 30px; height: 30px; background: #f3f4f6; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: bold; color: #6b7280; font-size: 0.8rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .search-card { flex-direction: column; padding: 20px; }
            .form-select-lg-custom, .btn-search-lg { width: 100%; }
            .hero-title { font-size: 1.8rem; }
        }
    </style>

    <div class="hero-section">
        <h1 class="hero-title">Arsip Digital Repository</h1>
        <p class="hero-subtitle">Sistem pencarian dan monitoring lokasi dokumen fisik secara realtime, akurat, dan terstruktur.</p>
    </div>

    <div class="search-card-container">
        <form action="" method="GET">
            <input type="hidden" name="module" value="<?php echo isset($_GET['module']) ? $_GET['module'] : ''; ?>">
            <div class="search-card">
                <div style="flex-grow: 1;">
                    <label class="d-block text-muted small font-weight-bold mb-2 ml-1" style="text-align:left;">PILIH DIVISI UNTUK DITAMPILKAN</label>
                    <select name="filter_divisi" class="form-select-lg-custom w-100">
                        <option value="">-- Tampilkan Semua Arsip --</option>
                        <?php foreach($data_laporan as $d): ?>
                            <option value="<?php echo $d['kode_divisi']; ?>" <?php echo ($selected_divisi == $d['kode_divisi']) ? 'selected' : ''; ?>>
                                <?php echo $d['kode_divisi']; ?> - <?php echo $d['divisi_nama']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="align-self: flex-end;">
                     <button type="submit" class="btn-search-lg">
                        <i class="fas fa-search mr-2"></i> Cari Data
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="content-wrapper">
        
        <div class="toolbar-clean">
            <div class="d-flex flex-wrap align-items-center">
                <div class="stat-badge"><i class="fas fa-file-alt"></i> <?php echo $total_dokumen; ?> Dokumen</div>
                <div class="stat-badge"><i class="fas fa-box"></i> <?php echo $total_box; ?> Box Fisik</div>
            </div>
            
            <div class="btn-group">
                 <button onclick="window.print()" class="btn btn-outline-dark btn-round btn-sm font-weight-bold mr-2">
                    <i class="fas fa-print mr-1"></i> Cetak
                </button>
                <button onclick="exportExcel('tabelArsipElegant')" class="btn btn-success btn-round btn-sm font-weight-bold">
                    <i class="fas fa-file-excel mr-1"></i> Export Excel
                </button>
            </div>
        </div>

        <div class="card-table">
            <div class="table-responsive">
                <table class="table-elegant" id="tabelArsipElegant">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="25%">Divisi & Unit</th>
                            <th width="15%">RF ID (Kode Box)</th>
                            <th width="30%">Nama Dokumen</th>
                            <th width="12%" class="text-center">Jml Bantex</th>
                            <th width="12%" class="text-center">Jml Box</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($data_tampil)): ?>
                            <tr><td colspan="6" class="text-center py-5 text-muted font-italic">Silakan pilih divisi di atas untuk melihat data.</td></tr>
                        <?php else: 
                            $no = 1;
                            foreach($data_tampil as $row) { 
                                // Loop Dokumen
                                foreach($row['dokumen'] as $index => $doc) {
                                    $isFirst = ($index === 0); // Cek baris pertama grup
                        ?>
                            <tr>
                                <td class="text-center">
                                    <div class="number-circle mx-auto"><?php echo $no++; ?></div>
                                </td>
                                
                                <td>
                                    <?php if($isFirst): ?>
                                        <span class="cell-title"><?php echo $row['divisi_nama']; ?></span>
                                        <span class="cell-subtitle">Kode: <?php echo $row['kode_divisi']; ?></span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if($isFirst): ?>
                                        <span class="rfid-tag"><i class="fas fa-wifi mr-1"></i> <?php echo $row['no_kotak']; ?></span>
                                    <?php endif; ?>
                                </td>
                                
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="far fa-file-alt text-muted mr-3"></i>
                                        <div>
                                            <span class="text-dark font-weight-bold"><?php echo $doc['nama']; ?></span>
                                            <span class="d-block small text-muted">Tahun Arsip: <?php echo $doc['tahun']; ?></span>
                                        </div>
                                    </div>
                                </td>

                                <td class="text-center">
                                    <?php if($isFirst): ?>
                                        <span class="font-weight-bold text-dark" style="font-size: 1.1rem;"><?php echo $row['jml_bantex']; ?></span>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="text-center">
                                    <?php if($isFirst): ?>
                                        <span class="font-weight-bold text-dark" style="font-size: 1.1rem;"><?php echo $row['jml_box']; ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php 
                                } // End Foreach Dokumen
                                
                                // Spacer Row (Opsional: Memberi jarak antar Divisi agar tidak terlalu padat)
                                echo '<tr><td colspan="6" style="padding:0; height:8px; background:#f9fafb; border:none;"></td></tr>';
                            } 
                        endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="text-center mt-4 text-muted small">
            &copy; 2025 Sistem Manajemen Arsip Terpadu
        </div>

    </div>

    <script>
    function exportExcel(tableID){
        var downloadLink;
        var dataType = 'application/vnd.ms-excel';
        var tableSelect = document.getElementById(tableID);
        var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
        
        var header = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><style>table, th, td {border: 1px solid #000; font-family: Arial;}</style></head><body>';
        var footer = '</body></html>';
        var finalHTML = header + tableSelect.outerHTML + footer;

        downloadLink = document.createElement("a");
        document.body.appendChild(downloadLink);

        if(navigator.msSaveOrOpenBlob){
            var blob = new Blob(['\ufeff', finalHTML], { type: dataType });
            navigator.msSaveOrOpenBlob( blob, 'Laporan_Arsip.xls');
        }else{
            downloadLink.href = 'data:' + dataType + ', ' + encodeURIComponent(finalHTML);
            downloadLink.download = 'Laporan_Arsip.xls';
            downloadLink.click();
        }
    }
    </script>

<?php } ?>