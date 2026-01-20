<?php
// Mencegah akses langsung
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('location: 404.html');
} else {
    // --- 1. DATA DUMMY ---
    $data_laporan = [
        [
            'id_transaksi' => 'TRX-2025-001',
            'divisi'       => 'Sekretariat Perusahaan (DSPN)',
            'kode_divisi'  => 'DSPN',
            'dokumen_utama'=> 'SK Direksi Pengangkatan', 
            'rf_id'        => 'RF-DSPN-01',
            'jml_bantex'   => 2,
            'jml_box'      => 1,
            'status'       => 'Terkirim',
            'history'      => [
                ['status' => 'Box Telah di Kirim', 'date' => '2025-01-20 14:00', 'user' => 'Kurir', 'color' => 'success'],
                ['status' => 'Packing Selesai', 'date' => '2025-01-20 10:00', 'user' => 'Staff', 'color' => 'primary']
            ]
        ],
        [
            'id_transaksi' => 'TRX-2025-002',
            'divisi'       => 'Teknologi Informasi (DTIS)',
            'kode_divisi'  => 'DTIS',
            'dokumen_utama'=> 'Kontrak Maintenance FO',
            'rf_id'        => 'RF-DTIS-88',
            'jml_bantex'   => 5,
            'jml_box'      => 1,
            'status'       => 'Terkirim',
            'history'      => [
                ['status' => 'Box Telah di Kirim', 'date' => '2025-01-19 12:00', 'user' => 'Kurir', 'color' => 'success']
            ]
        ],
        [
            'id_transaksi' => 'TRX-2025-003',
            'divisi'       => 'Hukum & Legal (DHKM)',
            'kode_divisi'  => 'DHKM',
            'dokumen_utama'=> 'Perjanjian Sewa Gedung',
            'rf_id'        => 'RF-DHKM-09',
            'jml_bantex'   => 10,
            'jml_box'      => 2,
            'status'       => 'Pending',
            'history'      => [
                ['status' => 'Box Menunggu Persetujuan', 'date' => '2025-01-20 09:00', 'user' => 'Sistem', 'color' => 'warning']
            ]
        ],
        [
            'id_transaksi' => 'TRX-2025-004',
            'divisi'       => 'Sumber Daya Manusia (DSDM)',
            'kode_divisi'  => 'DSDM',
            'dokumen_utama'=> 'Data Karyawan Baru 2025',
            'rf_id'        => '-',
            'jml_bantex'   => 1,
            'jml_box'      => 1,
            'status'       => 'Progres',
            'history'      => [
                ['status' => 'Box Masih di Input', 'date' => '2025-01-20 08:30', 'user' => 'Staff HR', 'color' => 'info']
            ]
        ],
        [
            'id_transaksi' => 'TRX-2025-005',
            'divisi'       => 'Keuangan (DKEU)',
            'kode_divisi'  => 'DKEU',
            'dokumen_utama'=> 'Laporan Pajak Tahunan',
            'rf_id'        => 'RF-DKEU-21',
            'jml_bantex'   => 4,
            'jml_box'      => 1,
            'status'       => 'Terkirim',
            'history'      => [
                ['status' => 'Box Telah di Kirim', 'date' => '2025-01-18 15:00', 'user' => 'Kurir', 'color' => 'success']
            ]
        ]
    ];

    // --- 2. LOGIKA FILTER & HITUNG TOTAL ---
    $selected_divisi = isset($_GET['filter_divisi']) ? $_GET['filter_divisi'] : '';
    $data_tampil = [];
    $total_dokumen = 0;
    $total_box_fisik = 0;

    foreach($data_laporan as $row) {
        if($selected_divisi == '' || $row['kode_divisi'] == $selected_divisi) {
            $data_tampil[] = $row;
            // Simulasi hitung total dokumen (misal 1 baris = 5 dokumen untuk contoh)
            // Atau bisa diambil dari $row['jml_bantex'] * rata2 dokumen
            $total_dokumen += ($row['jml_bantex'] * 5); 
            $total_box_fisik += $row['jml_box'];
        }
    }
?>

    <style>
        /* --- GLOBAL LAYOUT --- */
        .main-panel > .content { padding: 0 !important; }
        .page-inner { padding: 25px 30px; width: 100%; max-width: 100%; }
        
        /* SEARCH & HERO */
        .hero-section { background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); padding: 60px 20px 80px; border-radius: 0 0 30px 30px; color: white; text-align: center; margin-bottom: -50px; }
        .search-card-container { max-width: 95%; margin: 0 auto 40px; position: relative; z-index: 10; }
        .search-card { background: white; padding: 25px; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); display: flex; gap: 15px; align-items: flex-end; }
        
        /* TOOLBAR (YANG ANDA MINTA JANGAN DIHILANGKAN) */
        .toolbar-clean { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .stat-badge { background: white; padding: 10px 20px; border-radius: 50px; font-size: 0.9rem; font-weight: 700; color: #1f2937; box-shadow: 0 2px 5px rgba(0,0,0,0.05); margin-right: 15px; display: inline-flex; align-items: center; transition: transform 0.2s; }
        .stat-badge:hover { transform: translateY(-2px); }
        .stat-badge i { color: #4f46e5; margin-right: 8px; font-size: 1.1rem; }

        /* TABLE STYLES */
        .card-table { background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid #f3f4f6; }
        .table-elegant { width: 100%; border-collapse: collapse; }
        .table-elegant th { padding: 18px 20px; text-align: left; font-size: 0.8rem; text-transform: uppercase; color: #6b7280; background: #f9fafb; border-bottom: 2px solid #f3f4f6; }
        .table-elegant td { padding: 18px 20px; border-bottom: 1px solid #f3f4f6; color: #1f2937; vertical-align: middle; }
        .table-elegant tr { cursor: pointer; transition: all 0.2s; }
        .table-elegant tr:hover { background-color: #f0f9ff; transform: scale(1.002); box-shadow: 0 4px 10px rgba(0,0,0,0.05); z-index: 5; position: relative; }

        /* STATUS BADGES */
        .status-badge { padding: 6px 12px; border-radius: 8px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; display: inline-block; text-align: center; width: 100%; }
        .st-terkirim { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .st-progres { background: #ffedd5; color: #9a3412; border: 1px solid #fed7aa; }
        .st-pending { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
        .status-desc { font-size: 0.65rem; display: block; font-weight: 500; margin-top: 2px; text-transform: capitalize; }

        /* MODAL & TABS */
        .modal-card-header { background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: white; padding: 25px; border-radius: 15px 15px 0 0; }
        .nav-tabs-custom { display: flex; background: white; padding: 0 25px; border-bottom: 1px solid #e2e8f0; }
        .nav-item-custom { padding: 15px 20px; font-weight: 600; color: #64748b; cursor: pointer; border-bottom: 3px solid transparent; transition: 0.3s; }
        .nav-item-custom.active { color: #2563eb; border-bottom-color: #2563eb; }
        .tab-pane { display: none; padding: 25px; }
        .tab-pane.active { display: block; animation: fadeIn 0.3s; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        /* EXPLORER GRID */
        .grid-container { display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 15px; }
        .grid-item { background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 15px; text-align: center; cursor: pointer; transition: 0.2s; }
        .grid-item:hover { border-color: #3b82f6; background: #eff6ff; transform: translateY(-3px); }
        .grid-icon { font-size: 28px; margin-bottom: 8px; display: block; }
        .grid-text { font-weight: 700; font-size: 13px; color: #334155; }

        /* TIMELINE */
        .t-item { position: relative; padding-bottom: 25px; padding-left: 30px; border-left: 2px solid #e2e8f0; }
        .t-item:last-child { border-left-color: transparent; }
        .t-icon { position: absolute; left: -11px; top: 0; width: 20px; height: 20px; border-radius: 50%; background: #3b82f6; border: 2px solid white; }
    </style>

    <div class="hero-section">
        <h1 style="font-weight:800;">Arsip Digital Repository</h1>
        <p style="opacity:0.9;">Monitoring dan Penelusuran Dokumen Fisik</p>
    </div>

    <div class="search-card-container">
        <form action="" method="GET" style="width:100%;">
            <div class="search-card">
                <div style="flex-grow:1;">
                    <label class="small font-weight-bold text-muted">FILTER DIVISI</label>
                    <select name="filter_divisi" class="form-control form-control-lg" style="background:#f9fafb;">
                        <option value="">-- Semua Divisi --</option>
                        <?php foreach($data_laporan as $d): ?>
                            <option value="<?= $d['kode_divisi'] ?>" <?= ($selected_divisi == $d['kode_divisi'])?'selected':''; ?>>
                                <?= $d['divisi'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-dark btn-lg" style="border-radius:10px;"><i class="fas fa-search"></i> Cari</button>
            </div>
        </form>
    </div>

    <div class="page-inner mt--5">
        
        <div class="toolbar-clean">
            <div class="d-flex flex-wrap align-items-center">
                <div class="stat-badge">
                    <i class="fas fa-file-alt"></i> <?= $total_dokumen ?> Dokumen
                </div>
                <div class="stat-badge">
                    <i class="fas fa-box"></i> <?= $total_box_fisik ?> Box Fisik
                </div>
            </div>
            
            <div class="btn-group">
                 <button onclick="window.print()" class="btn btn-outline-dark btn-round btn-sm font-weight-bold mr-2">
                    <i class="fas fa-print mr-1"></i> Cetak
                </button>
                <button onclick="exportExcel('tblArsip')" class="btn btn-success btn-round btn-sm font-weight-bold">
                    <i class="fas fa-file-excel mr-1"></i> Export Excel
                </button>
            </div>
        </div>
        <div class="card-table">
            <div class="table-responsive">
                <table class="table-elegant" id="tblArsip">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="15%">ID Transaksi</th>
                            <th width="20%">Divisi</th>
                            <th width="20%">Nama Dokumen</th>
                            <th width="12%">RF ID</th>
                            <th width="8%" class="text-center">Jml Bantex</th>
                            <th width="8%" class="text-center">Jml Box</th>
                            <th width="12%" class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if(empty($data_tampil)) {
                            echo '<tr><td colspan="8" class="text-center py-5 text-muted">Data tidak ditemukan</td></tr>';
                        } else {
                            $no = 1;
                            foreach($data_tampil as $row) {
                                // Tentukan style status (Selesai dihapus)
                                $stClass = ''; $stDesc = '';
                                switch($row['status']) {
                                    case 'Terkirim': $stClass = 'st-terkirim'; $stDesc = 'Box Telah di Kirim'; break;
                                    case 'Progres': $stClass = 'st-progres'; $stDesc = 'Box Masih di Input'; break;
                                    case 'Pending': $stClass = 'st-pending'; $stDesc = 'Box Menunggu Persetujuan'; break;
                                }
                                $jsonData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                        ?>
                            <tr onclick="openModal(<?= $jsonData ?>)">
                                <td class="text-center font-weight-bold text-muted"><?= $no++; ?></td>
                                <td><span class="badge badge-light border text-dark font-weight-bold"><?= $row['id_transaksi'] ?></span></td>
                                <td class="font-weight-bold text-dark"><?= $row['divisi'] ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="far fa-file-alt text-muted mr-2"></i> 
                                        <?= $row['dokumen_utama'] ?>
                                    </div>
                                </td>
                                <td><span class="badge badge-light border"><?= $row['rf_id'] ?></span></td>
                                <td class="text-center font-weight-bold"><?= $row['jml_bantex'] ?></td>
                                <td class="text-center font-weight-bold"><?= $row['jml_box'] ?></td>
                                <td class="text-center">
                                    <div class="status-badge <?= $stClass ?>">
                                        <?= $row['status'] ?>
                                        <span class="status-desc"><?= $stDesc ?></span>
                                    </div>
                                </td>
                            </tr>
                        <?php 
                            }
                        } 
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0" style="border-radius: 15px; overflow: hidden;">
                
                <div class="modal-card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge badge-warning text-white mb-2 px-3 py-1" id="m_id" style="border-radius: 20px;">TRX-000</span>
                            <h5 class="font-weight-bold text-white mb-0" id="m_divisi">Nama Divisi</h5>
                        </div>
                        <button type="button" class="close text-white opacity-1" data-dismiss="modal" style="font-size: 1.5rem;">&times;</button>
                    </div>
                </div>

                <div class="nav-tabs-custom">
                    <div class="nav-item-custom active" onclick="switchTab('lacak')">
                        <i class="fas fa-map-marker-alt mr-2"></i> Lacak Status
                    </div>
                    <div class="nav-item-custom" onclick="switchTab('isi')">
                        <i class="fas fa-folder-open mr-2"></i> Lihat Isi Box
                    </div>
                </div>

                <div class="modal-body bg-light" style="min-height: 400px;">
                    
                    <div id="tab-lacak" class="tab-pane active">
                        <div class="row mb-4">
                            <div class="col-4">
                                <div class="card card-stats card-round mb-0">
                                    <div class="card-body p-3 text-center">
                                        <div class="text-muted small font-weight-bold">JUMLAH BOX</div>
                                        <h3 class="font-weight-bold text-primary mb-0" id="m_jmlBox">0</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card card-stats card-round mb-0">
                                    <div class="card-body p-3 text-center">
                                        <div class="text-muted small font-weight-bold">JUMLAH BANTEX</div>
                                        <h3 class="font-weight-bold text-info mb-0" id="m_jmlBantex">0</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card card-stats card-round mb-0">
                                    <div class="card-body p-3 text-center">
                                        <div class="text-muted small font-weight-bold">RF ID</div>
                                        <h4 class="font-weight-bold text-success mb-0" id="m_rfid">-</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded border">
                            <h6 class="font-weight-bold mb-3">Riwayat Status</h6>
                            <div id="timelineContainer"></div>
                        </div>
                    </div>

                    <div id="tab-isi" class="tab-pane">
                        <div id="view-box">
                            <div class="text-center mb-3">
                                <h6 class="font-weight-bold text-muted">Pilih Box untuk melihat detail</h6>
                            </div>
                            <div class="grid-container" id="gridBox"></div>
                        </div>

                        <div id="view-bantex" style="display:none;">
                            <div class="d-flex align-items-center mb-3">
                                <button class="btn btn-sm btn-icon btn-round btn-light border mr-3" onclick="showView('view-box')"><i class="fas fa-arrow-left"></i></button>
                                <h6 class="font-weight-bold mb-0">Isi Box 1</h6>
                            </div>
                            <div class="grid-container" id="gridBantex"></div>
                        </div>

                        <div id="view-doc" style="display:none;">
                            <div class="d-flex align-items-center mb-3">
                                <button class="btn btn-sm btn-icon btn-round btn-light border mr-3" onclick="showView('view-bantex')"><i class="fas fa-arrow-left"></i></button>
                                <h6 class="font-weight-bold mb-0">Daftar Dokumen</h6>
                            </div>
                            <div class="bg-white rounded border overflow-hidden">
                                <table class="table table-hover mb-0">
                                    <tbody id="tableDoc"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        // Data Global untuk Modal
        let currentData = null;

        function openModal(data) {
            currentData = data;
            
            // Populate Header & Lacak Info
            $('#m_id').text(data.id_transaksi);
            $('#m_divisi').text(data.divisi);
            $('#m_jmlBox').text(data.jml_box);
            $('#m_jmlBantex').text(data.jml_bantex);
            $('#m_rfid').text(data.rf_id);

            // Populate Timeline
            let htmlTimeline = '';
            if(data.history) {
                data.history.forEach(h => {
                    htmlTimeline += `
                    <div class="t-item">
                        <div class="t-icon"></div>
                        <div>
                            <div class="font-weight-bold text-dark">${h.status}</div>
                            <small class="text-muted">${h.date}</small>
                            <div class="small text-primary font-weight-bold">${h.user}</div>
                        </div>
                    </div>`;
                });
            } else {
                htmlTimeline = '<div class="text-center text-muted">Belum ada riwayat.</div>';
            }
            $('#timelineContainer').html(htmlTimeline);

            // Reset Tab & View
            switchTab('lacak');
            generateBox(data.jml_box);
            
            $('#modalDetail').modal('show');
        }

        // Logic Tab Switcher
        function switchTab(tab) {
            $('.nav-item-custom').removeClass('active');
            $('.tab-pane').removeClass('active');
            
            if(tab === 'lacak') {
                $('.nav-item-custom').eq(0).addClass('active');
                $('#tab-lacak').addClass('active');
            } else {
                $('.nav-item-custom').eq(1).addClass('active');
                $('#tab-isi').addClass('active');
                showView('view-box'); // Reset explorer ke awal
            }
        }

        // Logic Explorer
        function showView(viewId) {
            $('#view-box, #view-bantex, #view-doc').hide();
            $('#' + viewId).fadeIn(200);
        }

        function generateBox(total) {
            let html = '';
            for(let i=1; i<=total; i++) {
                html += `
                <div class="grid-item" onclick="openBantex(${i})">
                    <span class="grid-icon text-primary"><i class="fas fa-box"></i></span>
                    <div class="grid-text">BOX ${i}</div>
                </div>`;
            }
            $('#gridBox').html(html);
        }

        function openBantex(boxNum) {
            let html = '';
            // Simulasi: 1 Box ada 5 Bantex
            for(let i=1; i<=5; i++) {
                html += `
                <div class="grid-item" onclick="openDoc(${boxNum}, ${i})">
                    <span class="grid-icon text-info"><i class="fas fa-folder"></i></span>
                    <div class="grid-text">BANTEX ${i}</div>
                </div>`;
            }
            $('#gridBantex').html(html);
            showView('view-bantex');
        }

        function openDoc(boxNum, bantexNum) {
            let html = '';
            // Simulasi Dokumen
            let docs = [
                'Dokumen Laporan Keuangan.pdf',
                'Surat Perjanjian Kerjasama.pdf',
                'Bukti Transaksi 2024.pdf'
            ];
            
            docs.forEach(d => {
                html += `
                <tr>
                    <td class="pl-4">
                        <i class="fas fa-file-pdf text-danger mr-2"></i> ${d}
                    </td>
                    <td class="text-right pr-4">
                        <button class="btn btn-sm btn-primary btn-round" onclick="downloadFile()"><i class="fas fa-download"></i></button>
                    </td>
                </tr>`;
            });
            
            $('#tableDoc').html(html);
            showView('view-doc');
        }

        function downloadFile() {
            swal("Download", "Sedang mengunduh file...", "success");
        }

        function exportExcel(tableID){
            var downloadLink;
            var dataType = 'application/vnd.ms-excel';
            var tableSelect = document.getElementById(tableID);
            var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
            
            var header = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><style>table, th, td {border: 1px solid #000; font-family: Arial;}</style></head><body>';
            var footer = '</body></html>';
            var finalHTML = header + tableHTML + footer;

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