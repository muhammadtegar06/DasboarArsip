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
        
            /* Kartu Statistik & Modal */
            .card-stat-modern { background: white; border-radius: 24px; padding: 30px 20px; text-align: center; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); border: 1px solid #f3f4f6; transition: transform 0.3s ease, box-shadow 0.3s ease; cursor: pointer; height: 100%; }
            .card-stat-modern:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1); border-color: #e5e7eb; }
        
            .icon-circle-modern { width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto; font-size: 24px; }
            .theme-cyan .icon-circle-modern { background-color: #ccfbf1; color: #0f766e; }
            .theme-green .icon-circle-modern { background-color: #dcfce7; color: #15803d; }
            .theme-orange .icon-circle-modern { background-color: #ffedd5; color: #c2410c; }
        
            .stat-number-modern { font-size: 36px; font-weight: 800; color: #111827; margin-bottom: 4px; line-height: 1; }
            .stat-label-modern { font-size: 14px; color: #6b7280; font-weight: 600; letter-spacing: 0.3px; }
        
            .avatar-divisi { width: 45px; height: 45px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 14px; box-shadow: 0 4px 6px rgba(118, 75, 162, 0.3); }
        
            .badge-pill-custom { padding: 6px 12px; border-radius: 50px; font-weight: 600; font-size: 11px; }
            .badge-soft-success { background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }

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
                        <span class="badge badge-success px-3 py-2 shadow-sm">Status: Approved</span>
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
                                    <th class="text-center py-3 border-0">Status</th>
                                    <th class="text-center py-3 border-0 rounded-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // --- QUERY MODIFIED: HANYA MENAMPILKAN STATUS 'Disetujui' ---
                                $query = mysqli_query($mysqli, "
                                SELECT 
                                    p.id, 
                                    p.no_pengajuan, 
                                    p.tanggal_pengajuan, 
                                    p.status,
                                    p.jumlah_box,
                                    d.nama_divisi, 
                                    d.singkatan_divisi,
                                    (SELECT COUNT(*) FROM tbl_bantex b 
                                     JOIN tbl_box bx ON b.id_box = bx.id 
                                     WHERE bx.id_pengajuan = p.id) as total_bantex
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
                                        $status = $data['status'];
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
                                                    <span class="badge badge-pill-custom badge-soft-success">SIAP INPUT</span>
                                                </td>

                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center align-items-center" style="gap: 8px;">
                                            
                                                        <button type="button"
                                                            onclick="bukaModalLihat('<?= $singkatan ?>', <?= $total_box ?>, <?= $total_bantex ?>)"
                                                            class="btn btn-soft-info btn-round btn-sm font-weight-bold px-3"
                                                            data-toggle="tooltip" title="Lihat Struktur">
                                                            <i class="fas fa-eye mr-1"></i> Lihat
                                                        </button>

                                                        <a href="?module=form_entri_barang_keluar&id=<?= $data['id'] ?>"
                                                            class="btn btn-soft-primary btn-round btn-sm font-weight-bold px-3"
                                                            data-toggle="tooltip" title="Input Dokumen & RFID">
                                                            <i class="fas fa-plus mr-1"></i> Input
                                                        </a>
                                            
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
        </div>

        <div class="modal fade" id="modalLihatDokumen" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content modal-content-elegant">
                    <div class="modal-header modal-header-elegant d-flex justify-content-between align-items-center">
                        <div class="text-white">
                            <h4 class="font-weight-bold mb-1"><i class="fas fa-layer-group mr-2"></i> Explorer Arsip</h4>
                            <p class="mb-0 small op-8">Visualisasi struktur penyimpanan box dan bantex.</p>
                        </div>
                        <button type="button" class="btn btn-icon btn-round btn-white text-dark shadow-sm"
                            data-dismiss="modal"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="modal-body modal-body-elegant">
                        <nav aria-label="breadcrumb" class="mb-4">
                            <ol class="breadcrumb bg-white shadow-sm rounded-pill px-4 py-2" id="navBreadcrumb"
                                style="font-size: 13px;">
                                <li class="breadcrumb-item active">Home</li>
                            </ol>
                        </nav>

                        <div id="view-divisi" class="view-section active">
                            <div class="text-center mb-5">
                                <h3 class="font-weight-bold text-dark mb-1" id="lblDivisiName">Nama Divisi</h3>
                                <p class="text-muted">Ringkasan volume arsip yang tersimpan</p>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-md-3 mb-4">
                                    <div class="card-stat-modern theme-green" onclick="navToBox()">
                                        <div class="icon-circle-modern"><i class="fas fa-box"></i></div>
                                        <div class="stat-number-modern" id="lblTotalBox">0</div>
                                        <div class="stat-label-modern">Total Box</div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-4">
                                    <div class="card-stat-modern theme-cyan" onclick="navToBox()">
                                        <div class="icon-circle-modern"><i class="fas fa-check-circle"></i></div>
                                        <div class="stat-number-modern" id="lblTotalBantex">0</div>
                                        <div class="stat-label-modern">Total Bantex</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="view-box" class="view-section">
                            <div class="d-flex align-items-center mb-4"><button
                                    class="btn btn-sm btn-icon btn-light rounded-circle mr-3" onclick="backToDivisi()"><i
                                        class="fas fa-arrow-left"></i></button>
                                <h5 class="font-weight-bold text-dark mb-0">Pilih Box Penyimpanan</h5>
                            </div>
                            <div class="row" id="containerBoxList"></div>
                        </div>

                        <div id="view-bantex" class="view-section">
                            <div class="d-flex align-items-center mb-4"><button
                                    class="btn btn-sm btn-icon btn-light rounded-circle mr-3" onclick="backToBox()"><i
                                        class="fas fa-arrow-left"></i></button>
                                <h5 class="font-weight-bold text-dark mb-0" id="lblCurrentBox">Isi Box</h5>
                            </div>
                            <div class="row" id="containerBantexList"></div>
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
                for (let i = 1; i <= totalBoxGlobal; i++) {
                    html += '<div class="col-md-3 mb-4"><div class="card item-card h-100 p-3 text-center cursor-pointer transition-hover" onclick="navToBantex(' + i + ')"><div class="item-icon-wrapper bg-icon-box mx-auto mt-2"><i class="fas fa-box fa-lg"></i></div><h6 class="font-weight-bold mt-3 mb-0 text-dark">Box ' + i + '</h6><small class="text-muted">Klik untuk lihat isi</small></div></div>';
                }
                $('#containerBoxList').html(html);
                showView('view-box');
                updateBreadcrumb(2);
            }

            function navToBantex(boxNum) {
                let html = '';
                let bantexCount = 6; 
                for (let i = 1; i <= bantexCount; i++) {
                    html += '<div class="col-md-3 mb-3"><div class="card item-card h-100 p-3 text-center cursor-pointer"><div class="item-icon-wrapper bg-icon-bantex mx-auto"><i class="fas fa-folder-open fa-lg"></i></div><h6 class="font-weight-bold mt-2 mb-0 text-dark">Bantex ' + i + '</h6><small class="text-muted">Dokumen Box ' + boxNum + '</small></div></div>';
                }
                $('#lblCurrentBox').text('Isi Box ' + boxNum);
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
                let crumb = '<li class="breadcrumb-item"><a href="#" onclick="backToDivisi()">Home</a></li>';
                if (level == 1) crumb = '<li class="breadcrumb-item active">Home</li>';
                if (level >= 2) crumb += (level == 2) ? '<li class="breadcrumb-item active">Box</li>' : '<li class="breadcrumb-item"><a href="#" onclick="backToBox()">Box</a></li>';
                if (level >= 3) crumb += '<li class="breadcrumb-item active">Bantex</li>';
                $('#navBreadcrumb').html(crumb);
            }

            $(document).ready(function () {
                $('[data-toggle="tooltip"]').tooltip();
            });
        </script>
<?php } ?>