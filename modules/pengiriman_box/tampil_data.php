<?php
// Mencegah akses langsung
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('location: 404.html');
} else {
    // --- DATA DUMMY PENGIRIMAN ---
    $data_pengiriman = [
        [
            'no_resi' => 'SHP-2026-001',
            'id_transaksi' => 'TRX-2026-001',
            'divisi' => 'DTIS - Divisi Teknologi Informasi',
            'singkatan' => 'DTIS',
            'tgl_kirim' => '2026-01-14',
            'total_box' => 5,
            'kurir' => 'Bpk. Herman (Driver)',
            'status_terkini' => 'Selesai',
            'history' => [
                ['waktu' => '14 Jan 14:00', 'status' => 'Box disimpan di Rak A-12', 'ket' => 'Admin Arsip', 'icon' => 'fas fa-check', 'color' => 'success'],
                ['waktu' => '14 Jan 13:30', 'status' => 'Diterima di Gudang Arsip', 'ket' => 'Security / Penerima', 'icon' => 'fas fa-warehouse', 'color' => 'primary'],
                ['waktu' => '14 Jan 10:00', 'status' => 'Dalam Perjalanan', 'ket' => 'Driver: Bpk. Herman', 'icon' => 'fas fa-truck', 'color' => 'warning'],
                ['waktu' => '14 Jan 09:00', 'status' => 'Paket Diserahkan ke Kurir', 'ket' => 'Staff DTIS', 'icon' => 'fas fa-box-open', 'color' => 'secondary']
            ]
        ],
        [
            'no_resi' => 'SHP-2026-002',
            'id_transaksi' => 'TRX-2026-004',
            'divisi' => 'DKEU - Divisi Keuangan',
            'singkatan' => 'DKEU',
            'tgl_kirim' => '2026-01-15',
            'total_box' => 2,
            'kurir' => 'Ekspedisi Internal',
            'status_terkini' => 'Dalam Perjalanan',
            'history' => [
                ['waktu' => '15 Jan 08:30', 'status' => 'Dalam Perjalanan', 'ket' => 'Driver: Bpk. Joko', 'icon' => 'fas fa-truck', 'color' => 'warning'],
                ['waktu' => '15 Jan 08:00', 'status' => 'Paket Diserahkan ke Kurir', 'ket' => 'Staff DKEU', 'icon' => 'fas fa-box-open', 'color' => 'secondary']
            ]
        ],
        [
            'no_resi' => 'SHP-2026-003',
            'id_transaksi' => 'TRX-2026-005',
            'divisi' => 'DSDM - Divisi SDM',
            'singkatan' => 'DSDM',
            'tgl_kirim' => '2026-01-15',
            'total_box' => 3,
            'kurir' => '-',
            'status_terkini' => 'Menunggu Pick-up',
            'history' => [
                ['waktu' => '15 Jan 09:00', 'status' => 'Permintaan Pick-up Dibuat', 'ket' => 'Staff DSDM', 'icon' => 'fas fa-clipboard-list', 'color' => 'secondary']
            ]
        ]
    ];
    ?>

    <style>
        /* --- Stylesheet Khusus --- */
        .avatar-divisi {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            box-shadow: 0 4px 6px rgba(118, 75, 162, 0.3);
        }

        /* Badge Status Soft */
        .badge-soft-success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            padding: 6px 12px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-soft-warning {
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
            padding: 6px 12px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge-soft-secondary {
            background-color: #f3f4f6;
            color: #374151;
            border: 1px solid #e5e7eb;
            padding: 6px 12px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 600;
        }

        /* Tombol Aksi */
        .btn-lacak {
            background-color: #eff6ff;
            color: #2563eb;
            border: none;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-lacak:hover {
            background-color: #dbeafe;
            color: #1d4ed8;
            transform: translateY(-1px);
        }

        /* --- TIMELINE CSS (Untuk Modal Lacak) --- */
        .timeline-modern {
            position: relative;
            padding-left: 30px;
            margin-top: 20px;
        }

        .timeline-modern::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e5e7eb;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 25px;
        }

        .timeline-icon {
            position: absolute;
            left: -30px;
            top: 0;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: white;
            border: 2px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
        }

        /* Warna Icon Timeline */
        .timeline-icon.success {
            border-color: #10b981;
            color: #10b981;
            background: #ecfdf5;
        }

        .timeline-icon.primary {
            border-color: #3b82f6;
            color: #3b82f6;
            background: #eff6ff;
        }

        .timeline-icon.warning {
            border-color: #f59e0b;
            color: #f59e0b;
            background: #fffbeb;
        }

        .timeline-icon.secondary {
            border-color: #6b7280;
            color: #6b7280;
            background: #f9fafb;
        }

        .timeline-content {
            background: white;
            border: 1px solid #f3f4f6;
            border-radius: 12px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .timeline-time {
            font-size: 12px;
            color: #9ca3af;
            margin-bottom: 4px;
            font-weight: 600;
        }

        .timeline-title {
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 2px;
            font-size: 14px;
        }

        .timeline-desc {
            font-size: 13px;
            color: #6b7280;
        }
    </style>

    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-top flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold"><i class="fas fa-shipping-fast mr-2"></i>Monitoring Pengiriman Box
                    </h2>
                    <h5 class="text-white op-7 mb-2">Lacak status perpindahan box fisik antar divisi dan gudang arsip.</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="page-inner mt--5">
        <div class="card border-0 shadow-sm rounded-lg" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 pb-2">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title font-weight-bold text-dark">Daftar Pengiriman (Outgoing)</h4>
                    <button class="btn btn-primary btn-round btn-sm"><i class="fas fa-plus mr-1"></i> Input
                        Pengiriman</button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center py-3 border-0 rounded-left">No.</th>
                                <th class="py-3 border-0">Info Resi & Divisi</th>
                                <th class="py-3 border-0">Kurir / Driver</th>
                                <th class="text-center py-3 border-0">Total Box</th>
                                <th class="text-center py-3 border-0">Status Terkini</th>
                                <th class="text-center py-3 border-0 rounded-right">Lacak</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($data_pengiriman as $index => $row) {
                                ?>
                                <tr>
                                    <td class="text-center font-weight-bold text-muted"><?= $no++; ?></td>

                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-divisi mr-3"><?= $row['singkatan'] ?></div>
                                            <div>
                                                <div class="font-weight-bold text-dark"><?= $row['no_resi'] ?></div>
                                                <small class="text-muted d-block">Ref: <?= $row['id_transaksi'] ?></small>
                                                <small
                                                    class="text-primary font-weight-bold"><?= date('d M Y', strtotime($row['tgl_kirim'])) ?></small>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="text-dark font-weight-bold"><?= $row['kurir'] ?></div>
                                        <small class="text-muted">Logistik</small>
                                    </td>

                                    <td class="text-center">
                                        <span class="badge badge-light border px-3">
                                            <i class="fas fa-box text-warning mr-1"></i> <?= $row['total_box'] ?> Box
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <?php if ($row['status_terkini'] == 'Selesai') { ?>
                                            <span class="badge-soft-success"><i class="fas fa-check-circle mr-1"></i>
                                                TERSIMPAN</span>
                                        <?php } elseif ($row['status_terkini'] == 'Dalam Perjalanan') { ?>
                                            <span class="badge-soft-warning"><i class="fas fa-truck mr-1"></i> ON DELIVERY</span>
                                        <?php } else { ?>
                                            <span class="badge-soft-secondary"><i class="fas fa-clock mr-1"></i> MENUNGGU</span>
                                        <?php } ?>
                                    </td>

                                    <td class="text-center">
                                        <button class="btn btn-sm btn-round btn-lacak px-3"
                                            onclick="bukaModalLacak(<?= $index ?>)">
                                            <i class="fas fa-search-location mr-1"></i> Lacak
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalLacak" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 20px;">
                <div class="modal-header border-0 bg-primary-gradient text-white" style="border-radius: 20px 20px 0 0;">
                    <div>
                        <h5 class="modal-title font-weight-bold" id="lblNoResi">SHP-XXXX-XXX</h5>
                        <p class="mb-0 small op-8" id="lblDivisi">Tracking History</p>
                    </div>
                    <button type="button" class="close text-white opacity-1" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body bg-light" style="max-height: 500px; overflow-y: auto;">
                    <div id="timelineContainer" class="timeline-modern">
                    </div>
                </div>
                <div class="modal-footer border-0 bg-white" style="border-radius: 0 0 20px 20px;">
                    <button type="button" class="btn btn-sm btn-secondary btn-round font-weight-bold"
                        data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mengubah data PHP ke JSON agar bisa dibaca Javascript
        const dbPengiriman = <?= json_encode($data_pengiriman); ?>;

        function bukaModalLacak(index) {
            let data = dbPengiriman[index];

            // Set Header Modal
            $('#lblNoResi').text(data.no_resi);
            $('#lblDivisi').text(data.divisi);

            // Generate Timeline HTML
            let html = '';

            data.history.forEach(item => {
                html += `
                <div class="timeline-item">
                    <div class="timeline-icon ${item.color}">
                        <i class="${item.icon}"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-time"><i class="far fa-clock mr-1"></i> ${item.waktu}</div>
                        <div class="timeline-title">${item.status}</div>
                        <div class="timeline-desc">Oleh: ${item.ket}</div>
                    </div>
                </div>
                `;
            });

            $('#timelineContainer').html(html);
            $('#modalLacak').modal('show');
        }

        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>

<?php } ?>