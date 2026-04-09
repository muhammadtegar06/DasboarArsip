<?php
// Mencegah akses langsung
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('location: 404.html');
} else {
?>

    <style>
        /* --- Stylesheet Khusus --- */
        .btn-soft-primary { background-color: #e0e7ff; color: #4338ca; border: none; transition: all 0.2s; }
        .btn-soft-primary:hover { background-color: #c7d2fe; color: #3730a3; transform: translateY(-2px); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
        .btn-soft-info { background-color: #cffafe; color: #0891b2; border: none; transition: all 0.2s; }
        .btn-soft-info:hover { background-color: #a5f3fc; color: #0e7490; transform: translateY(-2px); box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
        
        /* Badge Status Styles */
        .badge-pill-custom { padding: 6px 12px; border-radius: 50px; font-weight: 700; font-size: 11px; letter-spacing: 0.5px; }
        .badge-soft-success { background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .badge-soft-warning { background-color: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }

        .avatar-divisi { width: 45px; height: 45px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 14px; box-shadow: 0 4px 6px rgba(118, 75, 162, 0.3); }

        /* --- MODAL EXPLORER STYLES --- */
        .modal-content-elegant { border: none; border-radius: 20px; overflow: hidden; background: #f8fafc; }
        .modal-header-elegant { background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); padding: 20px 25px; border: none; }
        .modal-body-elegant { padding: 30px 40px; min-height: 450px; }
        
        .view-section { display: none; opacity: 0; transition: opacity 0.3s ease; }
        .view-section.active { display: block; opacity: 1; animation: slideUp 0.4s ease-out; }
        @keyframes slideUp { from { transform: translateY(15px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        /* CUSTOM CSS GRID (Anti-Gepeng) */
        .explorer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 20px;
            width: 100%;
            padding: 10px 0;
        }

        .item-card { 
            background: white; border-radius: 16px; border: 1px solid #e2e8f0; 
            transition: all 0.3s ease; box-shadow: 0 4px 6px rgba(0,0,0,0.02); 
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            padding: 25px 15px; cursor: pointer; height: 100%;
        }
        .item-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.08); border-color: #cbd5e1; }
        
        .item-icon-wrapper { width: 65px; height: 65px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 15px; }
        .bg-icon-box { background-color: #fff7ed; color: #ea580c; }
        .bg-icon-bantex { background-color: #eff6ff; color: #2563eb; }
        
        /* Text inside grid */
        .grid-title { font-size: 18px; font-weight: 800; color: #1e293b; margin-bottom: 5px; text-align: center; }
        .grid-subtitle { font-size: 12px; color: #64748b; text-align: center; }
    </style>

    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-top flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold"><i class="fas fa-archive mr-2"></i> Repository Arsip</h2>
                    <h5 class="text-white op-7 mb-2">Manajemen pengisian data fisik (RFID & Bantex).</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="page-inner mt--5">
        <div class="card border-0 shadow-sm rounded-lg" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 pb-2" style="border-radius: 15px 15px 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title font-weight-bold text-dark">Daftar Box Siap Input</h4>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center py-3 border-0 rounded-left">No.</th>
                                <th class="py-3 border-0">Divisi & Tanggal</th>
                                <th class="text-center py-3 border-0">Volume</th>
                                <th class="text-center py-3 border-0">Progres Input</th>
                                <th class="text-center py-3 border-0 rounded-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Penambahan Subquery untuk mengecek kelengkapan Label & Dokumen per Bantex
                            $query = mysqli_query($mysqli, "
                                SELECT 
                                    p.id, p.no_pengajuan, p.tanggal_pengajuan, p.status, p.jumlah_box,
                                    d.nama_divisi, d.singkatan_divisi,
                                    (SELECT COUNT(*) FROM tbl_bantex b JOIN tbl_box bx ON b.id_box = bx.id WHERE bx.id_pengajuan = p.id) as total_bantex,
                                    
                                    (SELECT COUNT(*) FROM tbl_box bx2 WHERE bx2.id_pengajuan = p.id AND (bx2.rfid_code IS NULL OR TRIM(bx2.rfid_code) = '')) as pending_rfid,
                                    
                                    (SELECT COUNT(*) FROM tbl_bantex b3 JOIN tbl_box bx3 ON b3.id_box = bx3.id WHERE bx3.id_pengajuan = p.id AND (b3.label_judul IS NULL OR TRIM(b3.label_judul) = '')) as pending_label,
                                    
                                    (SELECT COUNT(*) FROM tbl_bantex b4 JOIN tbl_box bx4 ON b4.id_box = bx4.id WHERE bx4.id_pengajuan = p.id AND NOT EXISTS (SELECT 1 FROM tbl_dokumen doc WHERE doc.id_bantex = b4.id)) as pending_dokumen

                                FROM tbl_pengajuan p
                                JOIN tbl_divisi d ON p.id_divisi = d.id
                                WHERE p.status = 'Disetujui' OR p.status = 'Diterima'
                                ORDER BY p.id DESC
                            ");

                            if (mysqli_num_rows($query) == 0) {
                                echo '<tr><td colspan="5" class="text-center py-5 text-muted">Tidak ada data box yang perlu diinput saat ini.</td></tr>';
                            } else {
                                $no = 1;
                                while ($data = mysqli_fetch_assoc($query)) {
                                    $id_trx = $data['no_pengajuan'];
                                    $singkatan = $data['singkatan_divisi'];
                                    $divisi = $data['nama_divisi'];
                                    $tanggal = date('d M Y', strtotime($data['tanggal_pengajuan']));
                                    $total_box = $data['jumlah_box'];
                                    $total_bantex = $data['total_bantex'];
                                    
                                    $pending_rfid = $data['pending_rfid'];
                                    $pending_label = $data['pending_label'];
                                    $pending_dokumen = $data['pending_dokumen'];
                                    
                                    // Semua kondisi harus 0 agar dianggap Selesai Diinput
                                    if ($pending_rfid == 0 && $pending_label == 0 && $pending_dokumen == 0 && $total_box > 0) {
                                        $status_label = '<span class="badge badge-pill-custom badge-soft-success"><i class="fas fa-check-double mr-1"></i> SELESAI DIINPUT</span>';
                                        $btn_text = "Edit Data";
                                        $btn_icon = "fa-edit";
                                    } else {
                                        $status_label = '<span class="badge badge-pill-custom badge-soft-warning"><i class="fas fa-clock mr-1"></i> SIAP INPUT</span>';
                                        $btn_text = "Input";
                                        $btn_icon = "fa-plus";
                                    }
                            ?>
                                <tr>
                                    <td class="text-center font-weight-bold text-muted"><?= $no++; ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-divisi mr-3"><?= $singkatan ?></div>
                                            <div>
                                                <div class="font-weight-bold text-dark"><?= $divisi ?></div>
                                                <div class="small text-muted"><i class="far fa-calendar-alt mr-1"></i> <?= $tanggal ?></div>
                                                <div class="small text-primary mt-1" style="font-family: monospace;"><?= $id_trx ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="badge badge-light border px-3 py-2">
                                            <i class="fas fa-box text-warning mr-1"></i> <?= $total_box ?> Box
                                            <span class="mx-1 text-muted">|</span>
                                            <i class="fas fa-folder text-primary mr-1"></i> <?= $total_bantex ?> Bantex
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <?= $status_label ?>
                                        
                                        <?php if($pending_rfid > 0): ?>
                                            <div class="mt-1 small text-danger font-weight-bold">(<?= $pending_rfid ?> Box belum diisi RFID)</div>
                                        <?php endif; ?>
                                        
                                        <?php if($pending_label > 0): ?>
                                            <div class="mt-1 small text-danger font-weight-bold">(<?= $pending_label ?> Bantex belum ada Label)</div>
                                        <?php endif; ?>
                                        
                                        <?php if($pending_dokumen > 0): ?>
                                            <div class="mt-1 small text-danger font-weight-bold">(<?= $pending_dokumen ?> Bantex belum diisi dokumen)</div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center" style="gap: 8px;">
                                            <button type="button" onclick="bukaModalLihat('<?= $singkatan ?>', <?= $total_box ?>, <?= $total_bantex ?>)" class="btn btn-soft-info btn-round btn-sm font-weight-bold px-3">
                                                <i class="fas fa-eye mr-1"></i> Lihat
                                            </button>
                                            <a href="?module=form_entri_barang_keluar&id=<?= $data['id'] ?>" class="btn btn-soft-primary btn-round btn-sm font-weight-bold px-3">
                                                <i class="fas <?= $btn_icon ?> mr-1"></i> <?= $btn_text ?>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php } } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalLihatDokumen" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content modal-content-elegant shadow-lg">
                <div class="modal-header modal-header-elegant d-flex justify-content-between align-items-center">
                    <div class="text-white">
                        <h4 class="font-weight-bold mb-1"><i class="fas fa-layer-group mr-2"></i> Explorer Arsip</h4>
                        <p class="mb-0 small" style="color: #bfdbfe;">Visualisasi struktur penyimpanan box dan bantex.</p>
                    </div>
                    <button type="button" class="btn btn-icon btn-round btn-white text-dark shadow-sm" data-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body modal-body-elegant">
                    
                    <nav aria-label="breadcrumb" class="mb-4">
                        <ol class="breadcrumb bg-white shadow-sm px-4 py-2" id="navBreadcrumb" style="font-size: 13px; border-radius: 10px; border: 1px solid #e2e8f0;">
                            <li class="breadcrumb-item active">Home</li>
                        </ol>
                    </nav>

                    <div id="view-divisi" class="view-section active">
                        <div class="text-center mb-5">
                            <h3 class="font-weight-bold text-dark mb-2" id="lblDivisiName">Nama Divisi</h3>
                            <p class="text-muted">Pilih kategori di bawah untuk melihat rincian arsip.</p>
                        </div>
                        
                        <div class="explorer-grid">
                            <div class="item-card" onclick="navToBox()">
                                <div class="item-icon-wrapper bg-icon-box"><i class="fas fa-box-open fa-2x"></i></div>
                                <div class="grid-title" id="lblTotalBox">0</div>
                                <div class="grid-subtitle">Total Box</div>
                            </div>
                            <div class="item-card" onclick="navToBox()">
                                <div class="item-icon-wrapper bg-icon-bantex"><i class="fas fa-folder-open fa-2x"></i></div>
                                <div class="grid-title" id="lblTotalBantex">0</div>
                                <div class="grid-subtitle">Total Bantex</div>
                            </div>
                        </div>
                    </div>

                    <div id="view-box" class="view-section">
                        <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
                            <button class="btn btn-sm btn-icon btn-light rounded-circle mr-3 shadow-sm" onclick="backToDivisi()">
                                <i class="fas fa-arrow-left text-dark"></i>
                            </button>
                            <h5 class="font-weight-bold text-dark mb-0">Pilih Box Penyimpanan</h5>
                        </div>
                        
                        <div class="explorer-grid" id="containerBoxList"></div>
                    </div>

                    <div id="view-bantex" class="view-section">
                        <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
                            <button class="btn btn-sm btn-icon btn-light rounded-circle mr-3 shadow-sm" onclick="backToBox()">
                                <i class="fas fa-arrow-left text-dark"></i>
                            </button>
                            <h5 class="font-weight-bold text-dark mb-0" id="lblCurrentBox">Isi Box</h5>
                        </div>
                        
                        <div class="explorer-grid" id="containerBantexList"></div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentDivisi = '';
        let totalBoxGlobal = 0;
        let totalBantexGlobal = 0;

        function bukaModalLihat(divisi, tBox, tBantex) {
            currentDivisi = divisi; 
            totalBoxGlobal = parseInt(tBox); 
            totalBantexGlobal = parseInt(tBantex);
            
            $('#lblDivisiName').text(divisi);
            $('#lblTotalBox').text(tBox + ' BOX');
            $('#lblTotalBantex').text(tBantex + ' BANTEX');
            
            showView('view-divisi');
            updateBreadcrumb(1);
            $('#modalLihatDokumen').modal('show');
        }

        function navToBox() {
            let html = '';
            for (let i = 1; i <= totalBoxGlobal; i++) {
                html += `
                    <div class="item-card" onclick="navToBantex(${i})">
                        <div class="item-icon-wrapper bg-icon-box">
                            <i class="fas fa-box fa-2x"></i>
                        </div>
                        <h6 class="grid-title">Box ${i}</h6>
                        <span class="badge badge-light border text-muted px-3 py-1 mt-1">Lihat Isi Bantex</span>
                    </div>`;
            }
            $('#containerBoxList').html(html);
            showView('view-box');
            updateBreadcrumb(2);
        }

        function navToBantex(boxNum) {
            let html = '';
            let bantexCount = totalBoxGlobal > 0 ? Math.ceil(totalBantexGlobal / totalBoxGlobal) : 0;
            if(bantexCount === 0) bantexCount = 1; 
            
            for (let i = 1; i <= bantexCount; i++) {
                html += `
                    <div class="item-card" style="cursor: default;">
                        <div class="item-icon-wrapper bg-icon-bantex">
                            <i class="fas fa-folder-open fa-2x"></i>
                        </div>
                        <h6 class="grid-title">Bantex ${i}</h6>
                        <div class="grid-subtitle mt-1">Dari Box ${boxNum}</div>
                    </div>`;
            }
            $('#lblCurrentBox').text('Daftar Bantex di Box ' + boxNum);
            $('#containerBantexList').html(html);
            showView('view-bantex');
            updateBreadcrumb(3);
        }

        function backToDivisi() { showView('view-divisi'); updateBreadcrumb(1); }
        function backToBox() { showView('view-box'); updateBreadcrumb(2); }

        function showView(viewId) {
            $('.view-section').removeClass('active');
            setTimeout(function () { $('#' + viewId).addClass('active'); }, 50);
        }

        function updateBreadcrumb(level) {
            let crumb = '<li class="breadcrumb-item"><a href="javascript:void(0)" class="text-primary font-weight-bold" onclick="backToDivisi()">Home</a></li>';
            if (level == 1) crumb = '<li class="breadcrumb-item active font-weight-bold text-dark">Home (Ringkasan)</li>';
            
            if (level >= 2) crumb += (level == 2) 
                ? '<li class="breadcrumb-item active font-weight-bold text-dark">Pilih Box</li>' 
                : '<li class="breadcrumb-item"><a href="javascript:void(0)" class="text-primary font-weight-bold" onclick="backToBox()">Pilih Box</a></li>';
                
            if (level >= 3) crumb += '<li class="breadcrumb-item active font-weight-bold text-dark">Daftar Bantex</li>';
            
            $('#navBreadcrumb').html(crumb);
        }

        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
<?php } ?>