<?php
// Mencegah akses langsung
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('location: 404.html');
} else {
    // --- 1. DATA DUMMY (Lengkap dengan History & RF ID) ---
    $data_dummy = [
        [
            'id_transaksi' => 'TRX-2025-001',
            'divisi'       => 'DTIS - Divisi Teknologi Informasi',
            'singkatan'    => 'DTIS',
            'tanggal'      => '2025-12-30',
            'total_box'    => 5,
            'jumlah'       => 30, // Total Bantex
            'status'       => 'Terkirim',
            'rf_id'        => 'RF-88190',
            'history'      => [
                ['status' => 'Box diterima di IndoArsip', 'date' => '2025-12-31 10:00', 'user' => 'Kurir Eksternal', 'icon' => 'fa-warehouse', 'color' => 'success'],
                ['status' => 'Pengiriman ke Gudang Pusat', 'date' => '2025-12-30 14:00', 'user' => 'Logistik', 'icon' => 'fa-truck', 'color' => 'primary'],
                ['status' => 'Approval Admin', 'date' => '2025-12-30 09:30', 'user' => 'Admin Arsip', 'icon' => 'fa-check-circle', 'color' => 'info'],
                ['status' => 'Input Data Pengajuan', 'date' => '2025-12-30 08:00', 'user' => 'Staff DTIS', 'icon' => 'fa-file-alt', 'color' => 'secondary']
            ]
        ],
        [
            'id_transaksi' => 'TRX-2025-002',
            'divisi'       => 'DSDM - Divisi Sumber Daya Manusia',
            'singkatan'    => 'DSDM',
            'tanggal'      => '2025-12-28',
            'total_box'    => 2,
            'jumlah'       => 12,
            'status'       => 'Progres',
            'rf_id'        => '-',
            'history'      => [
                ['status' => 'Sedang Input Dokumen', 'date' => '2025-12-28 11:00', 'user' => 'Staff DSDM', 'icon' => 'fa-keyboard', 'color' => 'warning']
            ]
        ],
        [
            'id_transaksi' => 'TRX-2025-003',
            'divisi'       => 'DHKM - Divisi Hukum & Legal',
            'singkatan'    => 'DHKM',
            'tanggal'      => '2025-12-25',
            'total_box'    => 10,
            'jumlah'       => 60,
            'status'       => 'Pending',
            'rf_id'        => '-',
            'history'      => [
                ['status' => 'Menunggu Approval Admin', 'date' => '2025-12-25 15:00', 'user' => 'System', 'icon' => 'fa-clock', 'color' => 'secondary'],
                ['status' => 'Input Selesai', 'date' => '2025-12-25 14:50', 'user' => 'Staff DHKM', 'icon' => 'fa-check', 'color' => 'info']
            ]
        ],
        [
            'id_transaksi' => 'TRX-2025-004',
            'divisi'       => 'DKEU - Divisi Keuangan',
            'singkatan'    => 'DKEU',
            'tanggal'      => '2025-12-20',
            'total_box'    => 3,
            'jumlah'       => 18,
            'status'       => 'Terkirim',
            'rf_id'        => 'RF-88201',
            'history'      => [
                ['status' => 'Box diterima di IndoArsip', 'date' => '2025-12-21 09:00', 'user' => 'Kurir', 'icon' => 'fa-warehouse', 'color' => 'success']
            ]
        ]
    ];
?>

    <style>
        /* --- STYLE STATUS & ANIMASI --- */
        @keyframes mobil-jalan { 0% { transform: translateX(0); } 50% { transform: translateX(4px); } 100% { transform: translateX(0); } }
        @keyframes jam-goyang { 0% { transform: rotate(0deg); } 25% { transform: rotate(15deg); } 50% { transform: rotate(0deg); } 75% { transform: rotate(-15deg); } 100% { transform: rotate(0deg); } }

        .badge-status-pill { padding: 8px 15px; border-radius: 50px; font-weight: 700; font-size: 11px; display: inline-flex; align-items: center; letter-spacing: 0.5px; transition: all 0.3s; cursor: help; border: 1px solid transparent; }
        .badge-status-pill:hover { box-shadow: 0 4px 6px rgba(0,0,0,0.08); transform: translateY(-1px); }
        .badge-status-pill i { margin-right: 6px; font-size: 12px; }

        .status-terkirim { background-color: #d1fae5; color: #065f46; border-color: #a7f3d0; }
        .status-terkirim:hover i { animation: mobil-jalan 0.8s ease-in-out infinite; color: #047857; }

        .status-progres { background-color: #ffedd5; color: #9a3412; border-color: #fed7aa; }
        .status-progres:hover i { transform: scale(1.3); transition: transform 0.3s; color: #c2410c; }

        .status-pending { background-color: #f3f4f6; color: #374151; border-color: #e5e7eb; }
        .status-pending:hover i { animation: jam-goyang 1s ease-in-out infinite; color: #1f2937; }

        /* --- STYLE BADGE ID --- */
        .badge-id { font-family: 'Courier New', monospace; font-weight: 700; background-color: #f8f9fa; border: 1px solid #e9ecef; color: #495057; padding: 5px 10px; border-radius: 6px; font-size: 13px; }
        .avatar-divisi { width: 45px; height: 45px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 14px; box-shadow: 0 4px 6px rgba(118, 75, 162, 0.3); }

        /* --- STYLE TOMBOL AKSI ELEGAN (BARU) --- */
        .btn-action-group {
            display: flex;
            gap: 8px; /* Jarak antar tombol */
            justify-content: center;
        }

        .btn-icon-soft {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-icon-soft i {
            font-size: 14px;
            transition: transform 0.3s;
        }

        .btn-icon-soft:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .btn-icon-soft:hover i {
            transform: scale(1.15);
        }

        /* Warna Tombol */
        .btn-lacak { background-color: #e0f2fe; color: #0284c7; } /* Biru Langit */
        .btn-lacak:hover { background-color: #0284c7; color: #fff; }

        .btn-lihat { background-color: #dcfce7; color: #166534; } /* Hijau Mint */
        .btn-lihat:hover { background-color: #166534; color: #fff; }

        .btn-pdf { background-color: #fee2e2; color: #b91c1c; } /* Merah Soft */
        .btn-pdf:hover { background-color: #b91c1c; color: #fff; }


        /* --- MODAL LACAK (Style Modern) --- */
        .modal-track-header { background-color: #2563eb; color: white; border-radius: 15px 15px 0 0; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; }
        .modal-track-content { background: #f8fafc; padding: 25px; }
        
        /* 3 Cards Info */
        .track-card { background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 15px; text-align: center; height: 100%; box-shadow: 0 2px 4px rgba(0,0,0,0.02); transition: 0.3s; }
        .track-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.05); }
        
        .track-icon { font-size: 24px; margin-bottom: 8px; display: block; }
        .track-label { font-size: 10px; text-transform: uppercase; color: #94a3b8; font-weight: 700; letter-spacing: 0.5px; }
        .track-value { font-size: 16px; font-weight: 800; margin-top: 5px; color: #1e293b; }
        
        .icon-blue { color: #2563eb; } .val-blue { color: #2563eb; }
        .icon-cyan { color: #0ea5e9; } .val-cyan { color: #0ea5e9; }
        .icon-green { color: #16a34a; } .val-green { color: #16a34a; }

        /* Timeline Vertical */
        .track-timeline { margin-top: 25px; background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 25px; }
        .t-item { position: relative; padding-bottom: 25px; padding-left: 35px; border-left: 2px solid #e2e8f0; }
        .t-item:last-child { border-left: 2px solid transparent; padding-bottom: 0; }
        .t-icon { position: absolute; left: -21px; top: 0; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 4px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.05); z-index: 2; font-size: 14px; }
        .t-content { display: flex; justify-content: space-between; align-items: start; }
        .t-status { font-weight: 700; font-size: 14px; color: #334155; margin-bottom: 3px; }
        .t-date { font-size: 11px; color: #64748b; display: block; }
        .t-user { font-size: 12px; font-weight: 600; color: #2563eb; }
        
        /* Warna Icon Timeline */
        .bg-success { background: #dcfce7 !important; color: #166534 !important; }
        .bg-primary { background: #dbeafe !important; color: #1e40af !important; }
        .bg-warning { background: #fef3c7 !important; color: #92400e !important; }
        .bg-info { background: #cffafe !important; color: #155e75 !important; }
        .bg-secondary { background: #f3f4f6 !important; color: #374151 !important; }
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
                                <th class="py-3 border-0">ID Transaksi</th>
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
                                // Encode data untuk JS
                                $jsonData = htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8');
                            ?>
                                <tr>
                                    <td class="text-center font-weight-bold text-muted"><?php echo $no++; ?></td>
                                    <td><span class="badge-id"><?php echo $data['id_transaksi']; ?></span></td>
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
                                        <?php if ($data['status'] == 'Terkirim') { ?>
                                            <span class="badge-status-pill status-terkirim" data-toggle="tooltip" title="Box Telah Di Kirim Ke IndoArsip">
                                                <i class="fas fa-shipping-fast"></i> TERKIRIM
                                            </span>
                                        <?php } elseif ($data['status'] == 'Progres') { ?>
                                            <span class="badge-status-pill status-progres" data-toggle="tooltip" title="Box masih dalam Input">
                                                <i class="fas fa-sync-alt fa-spin"></i> PROGRES
                                            </span>
                                        <?php } else { ?>
                                            <span class="badge-status-pill status-pending" data-toggle="tooltip" title="Box Belum Di Setujui Admin">
                                                <i class="far fa-clock"></i> PENDING
                                            </span>
                                        <?php } ?>
                                    </td>
                                    
                                    <td class="text-center">
                                        <div class="btn-action-group">
                                            <button onclick="bukaModalLacak(<?= $jsonData ?>)" class="btn-icon-soft btn-lacak" data-toggle="tooltip" title="Lacak Lokasi">
                                                <i class="fas fa-search-location"></i>
                                            </button>
                                            
                                            <button onclick="bukaModalLihat()" class="btn-icon-soft btn-lihat" data-toggle="tooltip" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            
                                            <button onclick="downloadPdf('<?= $data['id_transaksi'] ?>')" class="btn-icon-soft btn-pdf" data-toggle="tooltip" title="Download PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </button>
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

    <div class="modal fade" id="modalLacak" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0" style="border-radius: 15px; overflow: hidden;">
                <div class="modal-track-header">
                    <h5 class="mb-0 font-weight-bold">Lacak Dokumen</h5>
                    <button type="button" class="close text-white opacity-1" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-track-content">
                    <div class="text-center mb-4">
                        <span class="badge badge-warning text-white mb-2 px-3 py-1" id="lblIdTransaksi" style="font-size: 12px; border-radius: 20px;">TRX-000</span>
                        <h5 class="font-weight-bold text-dark mb-1" id="lblJudul">Pengiriman Arsip</h5>
                        <p class="text-muted small" id="lblDivisiName">Divisi</p>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="track-card">
                                <i class="fas fa-box track-icon icon-blue"></i>
                                <div class="track-label">JUMLAH BOX</div>
                                <div class="track-value val-blue" id="lblJmlBox">0</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="track-card">
                                <i class="fas fa-folder track-icon icon-cyan"></i>
                                <div class="track-label">JUMLAH BANTEX</div>
                                <div class="track-value val-cyan" id="lblJmlBantex">0</div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="track-card">
                                <i class="fas fa-barcode track-icon icon-green"></i>
                                <div class="track-label">RF ID</div>
                                <div class="track-value val-green" id="lblRfid">-</div>
                            </div>
                        </div>
                    </div>

                    <div class="track-timeline">
                        <div id="timelineContainer">
                            </div>
                    </div>
                </div>
                
                <div class="modal-footer bg-white border-top-0 justify-content-center pb-4">
                    <button type="button" class="btn btn-secondary btn-round px-5" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip(); 
        });

        // 1. FUNGSI LACAK (Populate Data ke Modal)
        function bukaModalLacak(data) {
            $('#lblIdTransaksi').text(data.id_transaksi);
            $('#lblJudul').text('Pengiriman Arsip ' + data.singkatan);
            $('#lblDivisiName').text(data.divisi);
            
            // Isi Kartu Info
            $('#lblJmlBox').text(data.total_box);
            $('#lblJmlBantex').text(data.jumlah);
            $('#lblRfid').text(data.rf_id);

            // Generate Timeline
            let html = '';
            if(data.history && data.history.length > 0){
                data.history.forEach(item => {
                    html += `
                    <div class="t-item">
                        <div class="t-icon bg-${item.color}">
                            <i class="fas ${item.icon}"></i>
                        </div>
                        <div class="t-content">
                            <div>
                                <div class="t-status">${item.status}</div>
                                <div class="t-date"><i class="far fa-clock mr-1"></i> ${item.date}</div>
                            </div>
                            <div class="t-user">${item.user}</div>
                        </div>
                    </div>`;
                });
            } else {
                html = '<div class="text-center text-muted">Belum ada riwayat status.</div>';
            }
            $('#timelineContainer').html(html);
            $('#modalLacak').modal('show');
        }

        // 2. FUNGSI DOWNLOAD PDF
        function downloadPdf(id) {
            swal({
                title: "Download PDF?",
                text: "Anda akan mengunduh laporan untuk ID: " + id,
                icon: "info",
                buttons: ["Batal", "Download"],
            })
            .then((willDownload) => {
                if (willDownload) {
                    swal("Berhasil!", "File PDF sedang diproses...", "success");
                }
            });
        }

        // 3. FUNGSI LIHAT (Placeholder)
        function bukaModalLihat() {
            swal("Info", "Fitur Lihat Detail Dokumen (Explorer) akan muncul di sini.", "info");
        }
    </script>
<?php } ?>