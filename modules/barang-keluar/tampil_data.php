<?php
// Tampil Data: Dashboard Monitoring (Logika Aksi Baru)
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('location: 404.html');
} else {
    // --- DATA DUMMY (Disesuaikan untuk tes semua status) ---
    $data_dummy = [
        [
            'id_transaksi' => 2025001,
            'divisi'       => 'DTIS - Divisi Teknologi Informasi',
            'singkatan'    => 'DTIS',
            'tanggal'      => '2025-12-30',
            'total_box'    => 5,
            'jumlah'       => 30,
            'status'       => 'Diterima'
        ],
        [
            'id_transaksi' => 2025002,
            'divisi'       => 'DSDM - Divisi Sumber Daya Manusia',
            'singkatan'    => 'DSDM',
            'tanggal'      => '2025-12-28',
            'total_box'    => 2,
            'jumlah'       => 12,
            'status'       => 'Pending'
        ],
        [
            'id_transaksi' => 2025003,
            'divisi'       => 'DHKM - Divisi Hukum & Legal',
            'singkatan'    => 'DHKM',
            'tanggal'      => '2025-12-25',
            'total_box'    => 10,
            'jumlah'       => 60,
            'status'       => 'Diterima'
        ],
        [
            'id_transaksi' => 2025004,
            'divisi'       => 'DKEU - Divisi Keuangan',
            'singkatan'    => 'DKEU',
            'tanggal'      => '2025-12-20',
            'total_box'    => 3,
            'jumlah'       => 18,
            'status'       => 'Ditolak'
        ],
        [
            'id_transaksi' => 2025005,
            'divisi'       => 'DPSR - Divisi PSR dan Plasma',
            'singkatan'    => 'DPSR',
            'tanggal'      => '2025-12-15',
            'total_box'    => 1,
            'jumlah'       => 6,
            'status'       => 'Pending'
        ]
    ];
?>

    <style>
        /* --- Stylesheet Khusus --- */
        
        /* 1. Tombol Soft UI (Elegant & Modern) */
        .btn-soft-primary { background-color: #e0e7ff; color: #4338ca; border: none; transition: all 0.2s; }
        .btn-soft-primary:hover { background-color: #c7d2fe; color: #3730a3; transform: translateY(-2px); box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        
        .btn-soft-info { background-color: #cffafe; color: #0891b2; border: none; transition: all 0.2s; }
        .btn-soft-info:hover { background-color: #a5f3fc; color: #0e7490; transform: translateY(-2px); box-shadow: 0 4px 6px rgba(0,0,0,0.1); }

        .btn-soft-danger { background-color: #fee2e2; color: #b91c1c; border: none; transition: all 0.2s; }
        .btn-soft-danger:hover { background-color: #fecaca; color: #991b1b; transform: translateY(-2px); box-shadow: 0 4px 6px rgba(0,0,0,0.1); }

        /* 2. Kartu Statistik */
        .card-stat-modern {
            background: white; border-radius: 24px; padding: 30px 20px; text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); border: 1px solid #f3f4f6;
            transition: transform 0.3s ease, box-shadow 0.3s ease; cursor: pointer; height: 100%;
        }
        .card-stat-modern:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1); border-color: #e5e7eb; }

        /* Icon & Typography */
        .icon-circle-modern { width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto; font-size: 24px; }
        .theme-cyan .icon-circle-modern { background-color: #ccfbf1; color: #0f766e; }
        .theme-green .icon-circle-modern { background-color: #dcfce7; color: #15803d; }
        .theme-orange .icon-circle-modern { background-color: #ffedd5; color: #c2410c; }
        .dashed-border { border: 2px dashed currentColor; width: 100%; height: 100%; border-radius: 50%; display: flex; align-items: center; justify-content: center; }

        .stat-number-modern { font-size: 36px; font-weight: 800; color: #111827; margin-bottom: 4px; line-height: 1; }
        .stat-label-modern { font-size: 14px; color: #6b7280; font-weight: 600; letter-spacing: 0.3px; }

        .avatar-divisi { width: 45px; height: 45px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 14px; box-shadow: 0 4px 6px rgba(118, 75, 162, 0.3); }
        .badge-soft-success { background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .badge-soft-warning { background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .badge-soft-danger  { background-color: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
        .badge-pill-custom { padding: 6px 12px; border-radius: 50px; font-weight: 600; font-size: 11px; }

        /* Modal View */
        .modal-content-elegant { border: none; border-radius: 24px; overflow: hidden; }
        .modal-header-elegant { background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); padding: 25px; border: none; }
        .modal-body-elegant { background-color: #f9fafb; padding: 40px; min-height: 500px; }
        .view-section { display: none; opacity: 0; transition: opacity 0.3s ease; }
        .view-section.active { display: block; opacity: 1; animation: slideUp 0.4s ease-out; }
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        
        .item-card { background: white; border-radius: 15px; border: 1px solid #f1f5f9; }
        .item-icon-wrapper { width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 10px; }
        .bg-icon-box { background-color: #fff7ed; color: #ea580c; }
        .bg-icon-bantex { background-color: #eff6ff; color: #2563eb; }
    </style>

    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-top flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold"><i class="fas fa-archive mr-2"></i> Repository Arsip</h2>
                    <h5 class="text-white op-7 mb-2">Manajemen penyimpanan arsip fisik dan digital.</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="page-inner mt--5">
        <div class="card border-0 shadow-sm rounded-lg" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 pb-2" style="border-radius: 15px 15px 0 0;">
                <h4 class="card-title font-weight-bold text-dark">Daftar Box & Bantex</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center py-3 border-0 rounded-left">No.</th>
                                <th class="py-3 border-0">Divisi & Tanggal</th>
                                <th class="text-center py-3 border-0">Volume</th>
                                <th class="text-center py-3 border-0">Status</th>
                                <th class="text-center py-3 border-0 rounded-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($data_dummy as $data) {
                                $singkatan = isset($data['singkatan']) ? $data['singkatan'] : 'DIV';
                            ?>
                                <tr>
                                    <td class="text-center font-weight-bold text-muted"><?php echo $no++; ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-divisi mr-3"><?php echo $singkatan; ?></div>
                                            <div>
                                                <div class="font-weight-bold text-dark"><?php echo $data['divisi']; ?></div>
                                                <div class="small text-muted"><i class="far fa-calendar-alt mr-1"></i> <?php echo date('d M Y', strtotime($data['tanggal'])); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="badge badge-light border px-3 py-2">
                                            <i class="fas fa-box text-warning mr-1"></i> <?php echo $data['total_box']; ?> Box
                                            <span class="mx-1 text-muted">|</span>
                                            <i class="fas fa-folder text-primary mr-1"></i> <?php echo $data['jumlah']; ?> Bantex
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($data['status'] == 'Diterima') { ?>
                                            <span class="badge badge-pill-custom badge-soft-success">DITERIMA</span>
                                        <?php } elseif ($data['status'] == 'Pending') { ?>
                                            <span class="badge badge-pill-custom badge-soft-warning">PENDING</span>
                                        <?php } else { ?>
                                            <span class="badge badge-pill-custom badge-soft-danger">DITOLAK</span>
                                        <?php } ?>
                                    </td>
                                    
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center" style="gap: 8px;">
                                            
                                            <?php if ($data['status'] == 'Diterima') { ?>
                                                <button type="button" 
                                                    onclick="bukaModalLihat('<?php echo $data['divisi']; ?>', <?php echo $data['total_box']; ?>, <?php echo $data['jumlah']; ?>)"
                                                    class="btn btn-soft-info btn-round btn-sm font-weight-bold px-3" data-toggle="tooltip" title="Lihat Detail">
                                                    <i class="fas fa-eye mr-1"></i> Lihat
                                                </button>

                                                <a href="?module=form_input_dokumen&id=<?php echo $data['id_transaksi']; ?>" 
                                                   class="btn btn-soft-primary btn-round btn-sm font-weight-bold px-3" data-toggle="tooltip" title="Input Dokumen">
                                                    <i class="fas fa-plus mr-1"></i> Input
                                                </a>

                                            <?php } elseif ($data['status'] == 'Pending') { ?>
                                                <button type="button" 
                                                    onclick="bukaModalLihat('<?php echo $data['divisi']; ?>', <?php echo $data['total_box']; ?>, <?php echo $data['jumlah']; ?>)"
                                                    class="btn btn-soft-info btn-round btn-sm font-weight-bold px-3" data-toggle="tooltip" title="Lihat Detail">
                                                    <i class="fas fa-eye mr-1"></i> Lihat
                                                </button>

                                                <a href="#" onclick="return confirm('Apakah Anda yakin ingin membatalkan pengajuan ini?')"
                                                   class="btn btn-soft-danger btn-round btn-sm font-weight-bold px-3" data-toggle="tooltip" title="Hapus Pengajuan">
                                                    <i class="fas fa-trash-alt mr-1"></i> Hapus
                                                </a>

                                            <?php } else { ?>
                                                <button type="button" 
                                                    onclick="bukaModalLihat('<?php echo $data['divisi']; ?>', <?php echo $data['total_box']; ?>, <?php echo $data['jumlah']; ?>)"
                                                    class="btn btn-soft-info btn-round btn-sm font-weight-bold px-4" data-toggle="tooltip" title="Lihat Detail">
                                                    <i class="fas fa-eye mr-1"></i> Lihat
                                                </button>
                                            <?php } ?>

                                        </div>
                                    </td>

                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalLihatDokumen" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content modal-content-elegant">
                <div class="modal-header modal-header-elegant d-flex justify-content-between align-items-center">
                    <div class="text-white">
                        <h4 class="font-weight-bold mb-1"><i class="fas fa-layer-group mr-2"></i> Explorer Arsip</h4>
                        <p class="mb-0 small op-8">Detail data penyimpanan dokumen fisik & digital.</p>
                    </div>
                    <button type="button" class="btn btn-icon btn-round btn-white text-dark shadow-sm" data-dismiss="modal"><i class="fas fa-times"></i></button>
                </div>
                <div class="modal-body modal-body-elegant">
                    <nav aria-label="breadcrumb" class="mb-4">
                        <ol class="breadcrumb bg-white shadow-sm rounded-pill px-4 py-2" id="navBreadcrumb" style="font-size: 13px;">
                            <li class="breadcrumb-item active">Home</li>
                        </ol>
                    </nav>
                    <div id="view-divisi" class="view-section active">
                        <div class="text-center mb-5"><h3 class="font-weight-bold text-dark mb-1" id="lblDivisiName">Nama Divisi</h3><p class="text-muted">Ringkasan volume arsip yang tersimpan</p></div>
                        <div class="row justify-content-center">
                            <div class="col-md-3 mb-4"><div class="card-stat-modern theme-green" onclick="navToBox()"><div class="icon-circle-modern"><i class="fas fa-box"></i></div><div class="stat-number-modern" id="lblTotalBox">0</div><div class="stat-label-modern">Total Box</div></div></div>
                            <div class="col-md-3 mb-4"><div class="card-stat-modern theme-cyan" onclick="navToBox()"><div class="icon-circle-modern"><i class="fas fa-check-circle"></i></div><div class="stat-number-modern" id="lblTotalBantex">0</div><div class="stat-label-modern">Total Bantex</div></div></div>
                            <div class="col-md-3 mb-4"><div class="card-stat-modern theme-orange"><div class="icon-circle-modern"><div class="dashed-border"><i class="fas fa-clock"></i></div></div><div class="stat-number-modern">2025</div><div class="stat-label-modern">Tahun Arsip</div></div></div>
                        </div>
                        <div class="text-center mt-4"><p class="text-muted small"><i class="fas fa-mouse-pointer mr-1"></i> Klik kartu <b>Total Box</b> untuk melihat rincian.</p></div>
                    </div>
                    <div id="view-box" class="view-section">
                        <div class="d-flex align-items-center mb-4"><button class="btn btn-sm btn-icon btn-light rounded-circle mr-3" onclick="backToDivisi()"><i class="fas fa-arrow-left"></i></button><h5 class="font-weight-bold text-dark mb-0">Pilih Box Penyimpanan</h5></div>
                        <div class="row" id="containerBoxList"></div>
                    </div>
                    <div id="view-bantex" class="view-section">
                         <div class="d-flex align-items-center mb-4"><button class="btn btn-sm btn-icon btn-light rounded-circle mr-3" onclick="backToBox()"><i class="fas fa-arrow-left"></i></button><h5 class="font-weight-bold text-dark mb-0" id="lblCurrentBox">Isi Box</h5></div>
                        <div class="row" id="containerBantexList"></div>
                    </div>
                    <div id="view-dokumen" class="view-section">
                        <div class="d-flex align-items-center mb-4"><button class="btn btn-sm btn-icon btn-light rounded-circle mr-3" onclick="backToBantex()"><i class="fas fa-arrow-left"></i></button><h5 class="font-weight-bold text-dark mb-0" id="lblCurrentBantex">Daftar Dokumen</h5></div>
                        <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
                            <div class="table-responsive"><table class="table table-hover mb-0"><thead class="bg-light"><tr><th class="pl-4">Nama Dokumen</th><th class="text-center">Tahun</th><th class="text-center">File</th></tr></thead><tbody id="containerDokumenList"></tbody></table></div>
                        </div>
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
            currentDivisi = divisi; totalBoxGlobal = tBox; totalBantexGlobal = tBantex;
            $('#lblDivisiName').text(divisi);
            $('#lblTotalBox').text(tBox);
            $('#lblTotalBantex').text(tBantex);
            showView('view-divisi');
            updateBreadcrumb(1);
            $('#modalLihatDokumen').modal('show');
        }

        function navToBox() {
            let html = '';
            for(let i=1; i<=totalBoxGlobal; i++) {
                html += '<div class="col-md-3 mb-4"><div class="card item-card h-100 p-3 text-center cursor-pointer transition-hover" onclick="navToBantex('+i+')"><div class="item-icon-wrapper bg-icon-box mx-auto mt-2"><i class="fas fa-box fa-lg"></i></div><h6 class="font-weight-bold mt-3 mb-0 text-dark">Box '+i+'</h6><small class="text-muted">Klik untuk lihat</small></div></div>';
            }
            $('#containerBoxList').html(html);
            showView('view-box');
            updateBreadcrumb(2);
        }

        function navToBantex(boxNum) {
            let html = '';
            let bantexPerBox = Math.ceil(totalBantexGlobal / totalBoxGlobal); 
            for(let i=1; i<=bantexPerBox; i++) {
                html += '<div class="col-md-3 mb-3"><div class="card item-card h-100 p-3 text-center cursor-pointer transition-hover" onclick="navToDokumen('+boxNum+', '+i+')"><div class="item-icon-wrapper bg-icon-bantex mx-auto"><i class="fas fa-folder-open fa-lg"></i></div><h6 class="font-weight-bold mt-2 mb-0 text-dark">Bantex '+i+'</h6></div></div>';
            }
            $('#lblCurrentBox').text('Isi Box ' + boxNum);
            $('#containerBantexList').html(html);
            window.currentBoxNum = boxNum; 
            showView('view-bantex');
            updateBreadcrumb(3);
        }

        function navToDokumen(boxNum, bantexNum) {
            let html = '<tr><td class="pl-4"><div class="d-flex align-items-center"><div class="btn btn-icon btn-sm btn-light text-danger mr-3"><i class="fas fa-file-pdf"></i></div><span class="font-weight-bold text-dark">Dokumen_Laporan_'+boxNum+'_'+bantexNum+'.pdf</span></div></td><td class="text-center font-weight-bold text-muted">2024</td><td class="text-center"><a href="#" class="btn btn-sm btn-soft-info rounded-pill px-3"><i class="fas fa-download mr-1"></i> Unduh</a></td></tr>';
            $('#lblCurrentBantex').text('Box ' + boxNum + ' / Bantex ' + bantexNum);
            $('#containerDokumenList').html(html);
            showView('view-dokumen');
            updateBreadcrumb(4);
        }

        function backToDivisi() { showView('view-divisi'); updateBreadcrumb(1); }
        function backToBox() { showView('view-box'); updateBreadcrumb(2); }
        function backToBantex() { showView('view-bantex'); updateBreadcrumb(3); }

        function showView(viewId) {
            $('.view-section').removeClass('active');
            setTimeout(function() { $('#' + viewId).addClass('active'); }, 50);
        }

        function updateBreadcrumb(level) {
            let crumb = '<li class="breadcrumb-item"><a href="#" onclick="backToDivisi()">Home</a></li>';
            if(level == 1) crumb = '<li class="breadcrumb-item active">Home</li>';
            if(level >= 2) crumb += (level==2) ? '<li class="breadcrumb-item active">Box</li>' : '<li class="breadcrumb-item"><a href="#" onclick="backToBox()">Box</a></li>';
            if(level >= 3) crumb += (level==3) ? '<li class="breadcrumb-item active">Bantex</li>' : '<li class="breadcrumb-item"><a href="#" onclick="backToBantex()">Bantex</a></li>';
            if(level >= 4) crumb += '<li class="breadcrumb-item active">Dokumen</li>';
            $('#navBreadcrumb').html(crumb);
        }
        
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
<?php } ?>