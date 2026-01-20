<?php
// mencegah direct access file PHP
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('location: 404.html');
} else {
    // --- DATA DUMMY (Updated dengan ID Transaksi) ---
    $dummy_data = [
        [
            "id_transaksi" => "TRX-2025-001", // ID Baru
            "divisi" => "Keuangan (DKEU)",
            "nama" => "Laporan Keuangan Tahunan 2023",
            "tahun" => "2023",
            "bantex" => "B-001-A",
            "box" => "BOX-KEU-2023",
            "rf_id" => "RF-88190",
            "history" => [
                ["status" => "Input Data", "date" => "2023-01-10 09:00", "user" => "Staf Keuangan"],
                ["status" => "Di-Acc Admin", "date" => "2023-01-11 14:30", "user" => "Admin Divisi"],
                ["status" => "Dikirim ke Indo Arsip", "date" => "2023-01-15 10:00", "user" => "Kurir Logistik"]
            ]
        ],
        [
            "id_transaksi" => "TRX-2025-002",
            "divisi" => "HRD (DSDM)",
            "nama" => "Rekap Absensi Karyawan Q1",
            "tahun" => "2024",
            "bantex" => "B-002-C",
            "box" => "BOX-HRD-2024",
            "rf_id" => "RF-88191",
            "history" => [
                ["status" => "Input Data", "date" => "2024-04-05 08:30", "user" => "Staf HRD"],
                ["status" => "Di-Acc Admin", "date" => "2024-04-06 11:00", "user" => "Admin Divisi"],
                ["status" => "Dikirim ke Indo Arsip", "date" => "2024-04-10 09:45", "user" => "Kurir Logistik"]
            ]
        ],
        [
            "id_transaksi" => "TRX-2025-003",
            "divisi" => "Teknologi Informasi (DTIS)",
            "nama" => "Dokumentasi Topologi Jaringan",
            "tahun" => "2022",
            "bantex" => "B-005-A",
            "box" => "BOX-IT-2022",
            "rf_id" => "RF-88192",
            "history" => [
                ["status" => "Input Data", "date" => "2022-12-20 13:00", "user" => "Staf IT"],
                ["status" => "Di-Acc Admin", "date" => "2022-12-21 09:00", "user" => "Admin Divisi"]
            ]
        ],
        [
            "id_transaksi" => "TRX-2025-004",
            "divisi" => "Marketing (DPSN)",
            "nama" => "Kontrak Kerjasama Vendor Iklan",
            "tahun" => "2024",
            "bantex" => "B-010-F",
            "box" => "BOX-MKT-2024",
            "rf_id" => "RF-88193",
            "history" => [
                ["status" => "Input Data", "date" => "2024-02-10 10:00", "user" => "Staf Marketing"]
            ]
        ],
        [
            "id_transaksi" => "TRX-2025-005",
            "divisi" => "Operasional (DOPR)",
            "nama" => "SOP Gudang & Logistik",
            "tahun" => "2021",
            "bantex" => "B-003-B",
            "box" => "BOX-OPS-2021",
            "rf_id" => "RF-88194",
            "history" => [
                ["status" => "Input Data", "date" => "2021-06-01 09:00", "user" => "Staf Ops"],
                ["status" => "Di-Acc Admin", "date" => "2021-06-02 10:00", "user" => "Admin Divisi"],
                ["status" => "Dikirim ke Indo Arsip", "date" => "2021-06-05 13:00", "user" => "Kurir Logistik"]
            ]
        ]
    ];
?>

    <style>
        /* CSS Khusus */
        
        /* 1. Badge ID Transaksi (Mirip Gambar Referensi) */
        .badge-trx {
            background-color: #f8f9fa; /* Abu-abu sangat muda */
            border: 1px solid #e9ecef;
            color: #495057;
            padding: 6px 12px;
            border-radius: 8px; /* Rounded corners */
            font-family: 'Courier New', monospace; /* Font monospace agar rapi */
            font-weight: 700;
            font-size: 13px;
            letter-spacing: 0.5px;
            display: inline-block;
        }

        /* 2. Tombol Lacak */
        .btn-track {
            background-color: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd;
            border-radius: 30px; font-weight: 600; font-size: 12px; padding: 6px 15px;
            transition: all 0.2s; display: inline-flex; align-items: center;
        }
        .btn-track:hover { background-color: #bae6fd; color: #0369a1; text-decoration: none; transform: translateY(-2px); }
        .btn-track i { margin-right: 5px; }

        /* 3. Timeline Styles */
        .tracking-timeline { padding: 20px 10px; position: relative; }
        .timeline-item { display: flex; gap: 15px; position: relative; padding-bottom: 25px; }
        .timeline-item::before {
            content: ''; position: absolute; left: 19px; top: 35px; bottom: 0;
            width: 2px; background-color: #e2e8f0; z-index: 1;
        }
        .timeline-item:last-child::before { display: none; }
        .timeline-icon {
            width: 40px; height: 40px; border-radius: 50%; 
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; z-index: 2; flex-shrink: 0;
            background-color: #dcfce7; color: #166534; border: 2px solid #bbf7d0;
        }
        .timeline-content { background: #f8fafc; padding: 12px 15px; border-radius: 8px; width: 100%; border: 1px solid #f1f5f9; }
        .timeline-title { font-weight: 700; color: #334155; margin-bottom: 2px; font-size: 14px; }
        .timeline-date { font-size: 11px; color: #64748b; margin-bottom: 0; }
        .timeline-user { font-size: 11px; color: #0284c7; font-weight: 600; float: right; }

        /* 4. Info Cards */
        .info-card-track { background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 15px; text-align: center; height: 100%; }
        .info-card-track .label { font-size: 10px; text-transform: uppercase; color: #94a3b8; font-weight: 700; letter-spacing: 0.5px; }
        .info-card-track .value { font-size: 16px; font-weight: 800; color: #1e293b; margin-top: 5px; }
        .icon-header { color: #3b82f6; font-size: 20px; margin-bottom: 8px; }
    </style>

    <div class="panel-header bg-secondary-gradient">
        <div class="page-inner py-45">
            <div class="d-flex align-items-left align-items-md-top flex-column flex-md-row">
                <div class="page-header text-white">
                    <h4 class="page-title text-white"><i class="fas fa-search-location mr-2"></i>Tracking Dokumen Arsip</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="page-inner mt--5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 pt-4">
                <div class="card-title font-weight-bold">Daftar Arsip Dokumen</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center" width="5%">No.</th>
                                <th width="18%">ID Transaksi</th> <th width="20%">Divisi</th>
                                <th>Nama Dokumen</th>
                                <th class="text-center">Periode</th>
                                <th class="text-center" width="15%">Lacak Dokumen</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($dummy_data as $data) { 
                                // Encode data ke JSON
                                $jsonData = htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8');
                            ?>
                                <tr>
                                    <td class="text-center text-muted"><?php echo $no++; ?></td>
                                    
                                    <td>
                                        <span class="badge-trx"><?php echo $data['id_transaksi']; ?></span>
                                    </td>

                                    <td>
                                        <div class="font-weight-bold text-dark"><?php echo $data['divisi']; ?></div>
                                    </td>
                                    
                                    <td><?php echo $data['nama']; ?></td>
                                    
                                    <td class="text-center">
                                        <span class="badge badge-light border"><?php echo $data['tahun']; ?></span>
                                    </td>
                                    
                                    <td class="text-center">
                                        <button class="btn-track" onclick="showTracking(<?php echo $jsonData; ?>)">
                                            <i class="fas fa-map-marker-alt"></i> Lacak Posisi
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

    <div class="modal fade" id="modalTracking" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-header bg-primary text-white" style="border-radius: 16px 16px 0 0;">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-search-location mr-2"></i> Lacak Dokumen</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body bg-light">
                    
                    <div class="text-center mb-4">
                        <span class="badge badge-warning text-white mb-2" id="trackIdTrx" style="font-size: 12px;">TRX-000</span>
                        <h5 class="font-weight-bold text-dark" id="trackNamaDoc">Nama Dokumen</h5>
                        <p class="text-muted small mb-0" id="trackDivisi">Divisi</p>
                    </div>

                    <div class="row mb-4">
                        <div class="col-4 px-1">
                            <div class="info-card-track">
                                <i class="fas fa-box icon-header"></i>
                                <div class="label">ID BOX</div>
                                <div class="value text-primary" id="trackBox">-</div>
                            </div>
                        </div>
                        <div class="col-4 px-1">
                            <div class="info-card-track">
                                <i class="fas fa-folder icon-header"></i>
                                <div class="label">KODE BANTEX</div>
                                <div class="value text-info" id="trackBantex">-</div>
                            </div>
                        </div>
                        <div class="col-4 px-1">
                            <div class="info-card-track">
                                <i class="fas fa-barcode icon-header"></i>
                                <div class="label">RF ID</div>
                                <div class="value text-success" id="trackRfid">-</div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-0">
                            <div class="tracking-timeline" id="timelineContainer">
                                </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-white justify-content-center" style="border-radius: 0 0 16px 16px;">
                    <button type="button" class="btn btn-secondary btn-round btn-sm px-4" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showTracking(data) {
            // 1. Isi Header Modal
            $('#trackIdTrx').text(data.id_transaksi);
            $('#trackNamaDoc').text(data.nama);
            $('#trackDivisi').text(data.divisi);

            // 2. Isi Info Lokasi
            $('#trackBox').text(data.box);
            $('#trackBantex').text(data.bantex);
            $('#trackRfid').text(data.rf_id);

            // 3. Generate Timeline HTML
            let timelineHtml = '';
            
            // Loop data history
            if (data.history && data.history.length > 0) {
                data.history.forEach((item, index) => {
                    let icon = '';
                    if(item.status.includes('Input')) icon = 'fa-file-alt';
                    else if(item.status.includes('Acc') || item.status.includes('Admin')) icon = 'fa-user-check';
                    else if(item.status.includes('Indo Arsip') || item.status.includes('Kirim')) icon = 'fa-truck';
                    else icon = 'fa-check';

                    timelineHtml += `
                    <div class="timeline-item">
                        <div class="timeline-icon">
                            <i class="fas ${icon}"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-user">${item.user}</div>
                            <div class="timeline-title">${item.status}</div>
                            <div class="timeline-date"><i class="far fa-clock mr-1"></i> ${item.date}</div>
                        </div>
                    </div>`;
                });
            } else {
                timelineHtml = '<div class="text-center text-muted p-3">Belum ada riwayat proses.</div>';
            }

            $('#timelineContainer').html(timelineHtml);

            // 4. Tampilkan Modal
            $('#modalTracking').modal('show');
        }
    </script>

<?php } ?>