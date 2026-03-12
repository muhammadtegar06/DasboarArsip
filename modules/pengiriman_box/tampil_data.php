<?php
// Mencegah akses langsung
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('location: 404.html');
} else {
    ?>

    <style>
        /* --- STYLE STATUS --- */
        .badge-status-pill {
            padding: 8px 15px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 11px;
            display: inline-flex;
            align-items: center;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            border: 1px solid transparent;
            width: 120px;
            justify-content: center;
            cursor: help;
            text-transform: uppercase;
        }

        /* Warna Status */
        .st-siap {
            background: #e0f2fe;
            color: #0284c7;
            border-color: #bae6fd;
        }

        .st-kirim {
            background: #dcfce7;
            color: #15803d;
            border-color: #bbf7d0;
        }

        /* Untuk To Send */
        .st-batal {
            background: #fee2e2;
            color: #b91c1c;
            border-color: #fecaca;
        }

        /* Untuk Cancel */
        .st-default {
            background: #f3f4f6;
            color: #374151;
            border-color: #d1d5db;
        }

        /* --- STYLE LAINNYA --- */
        .badge-id {
            font-family: 'Courier New', monospace;
            font-weight: 700;
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            color: #495057;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 13px;
        }

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

        .btn-action-group {
            display: flex;
            gap: 5px;
            justify-content: center;
        }

        .btn-icon-soft {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .btn-icon-soft:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-lacak {
            background: #e0f2fe;
            color: #0284c7;
        }

        .btn-lacak:hover {
            background: #0284c7;
            color: #fff;
        }

        .btn-edit {
            background: #ffedd5;
            color: #c2410c;
        }

        .btn-edit:hover {
            background: #ea580c;
            color: #fff;
        }

        /* Modal Style & Timeline */
        .modal-header-status {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            border: none;
        }

        .modal-track-header {
            background-color: #2563eb;
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-track-content {
            background: #f8fafc;
            padding: 25px;
        }

        .track-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 15px;
            text-align: center;
            height: 100%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .track-label {
            font-size: 10px;
            text-transform: uppercase;
            color: #94a3b8;
            font-weight: 700;
        }

        .track-value {
            font-size: 16px;
            font-weight: 800;
            margin-top: 5px;
            color: #1e293b;
        }

        .track-timeline {
            margin-top: 25px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 25px;
        }

        .t-item {
            position: relative;
            padding-bottom: 25px;
            padding-left: 35px;
            border-left: 2px solid #e2e8f0;
        }

        .t-item:last-child {
            border-left: 2px solid transparent;
            padding-bottom: 0;
        }

        .t-icon {
            position: absolute;
            left: -21px;
            top: 0;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 4px solid white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            z-index: 2;
            font-size: 14px;
        }

        .t-content {
            display: flex;
            justify-content: space-between;
            align-items: start;
        }

        .t-status {
            font-weight: 700;
            font-size: 14px;
            color: #334155;
        }

        .t-date {
            font-size: 11px;
            color: #64748b;
        }

        .bg-success {
            background: #16a34a !important;
            color: #fff !important;
        }

        .bg-info {
            background: #0ea5e9 !important;
            color: #fff !important;
        }

        .bg-primary {
            background: #2563eb !important;
            color: #fff !important;
        }

        .bg-danger {
            background: #dc2626 !important;
            color: #fff !important;
        }

        .bg-secondary {
            background: #64748b !important;
            color: #fff !important;
        }

        .icon-blue {
            color: #2563eb;
        }

        .val-blue {
            color: #2563eb;
        }

        .icon-cyan {
            color: #0ea5e9;
        }

        .val-cyan {
            color: #0ea5e9;
        }

        .icon-green {
            color: #16a34a;
        }

        .val-green {
            color: #16a34a;
        }
    </style>

    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-top flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold"><i class="fas fa-shipping-fast mr-2"></i> Pengiriman Box</h2>
                    <h5 class="text-white op-7 mb-2">Manajemen status pengiriman box ke gudang arsip.</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="page-inner mt--5">
        <div class="card border-0 shadow-sm rounded-lg" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 pb-2">
                <h4 class="card-title font-weight-bold text-dark">Daftar Pengiriman</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center py-3">No</th>
                                <th class="py-3">ID Transaksi</th>
                                <th class="py-3">Divisi & Tanggal</th>
                                <th class="text-center py-3">Volume</th>
                                <th class="text-center py-3">Status Pengiriman</th>
                                <th class="text-center py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Query disesuaikan agar membaca status "To Send", "Cancel", "Siap Kirim"
                            $query = mysqli_query($mysqli, "
                                SELECT 
                                    p.id, p.no_pengajuan, p.tanggal_pengajuan, p.jumlah_box, p.status,
                                    d.nama_divisi, d.singkatan_divisi,
                                    (SELECT COUNT(*) FROM tbl_bantex b JOIN tbl_box bx ON b.id_box = bx.id WHERE bx.id_pengajuan = p.id) as total_bantex,
                                    (SELECT rfid_code FROM tbl_box bx3 WHERE bx3.id_pengajuan = p.id LIMIT 1) as sample_rfid
                                FROM tbl_pengajuan p
                                JOIN tbl_divisi d ON p.id_divisi = d.id
                                WHERE 
                                    p.status IN ('Disetujui', 'Diterima', 'Siap Kirim', 'To Send', 'Cancel', 'Telah Dikirim', 'Dibatalkan')
                                ORDER BY p.id DESC
                            ");

                            if (mysqli_num_rows($query) == 0) {
                                echo '<tr><td colspan="6" class="text-center py-4 text-muted">Belum ada data pengajuan yang diproses.</td></tr>';
                            } else {
                                $no = 1;
                                while ($data = mysqli_fetch_assoc($query)) {
                                    $id_trx = $data['no_pengajuan'];
                                    $id_pengajuan = $data['id'];

                                    // Tentukan Label Status & Warna
                                    $db_status = $data['status'];
                                    $badge_class = 'st-default';
                                    $icon = 'fa-check';
                                    $label = 'DISETUJUI';
                                    $tooltip_text = "Pengajuan telah disetujui, menunggu proses.";

                                    // Support nama status baru & status lama yang tersimpan
                                    if ($db_status == 'To Send' || $db_status == 'Telah Dikirim') {
                                        $badge_class = 'st-kirim';
                                        $icon = 'fa-truck';
                                        $label = 'TO SEND';
                                        $tooltip_text = 'Sedang dalam pengiriman ke gudang';
                                    } elseif ($db_status == 'Cancel' || $db_status == 'Dibatalkan') {
                                        $badge_class = 'st-batal';
                                        $icon = 'fa-times-circle';
                                        $label = 'CANCEL';
                                        $tooltip_text = 'Proses pengiriman dibatalkan';
                                    } elseif ($db_status == 'Siap Kirim') {
                                        $badge_class = 'st-siap';
                                        $icon = 'fa-box-open';
                                        $label = 'SIAP KIRIM';
                                        $tooltip_text = 'Data fisik siap untuk dikirim';
                                    }

                                    // ==========================================
                                    // PEMBENTUKAN TIMELINE REAL-TIME
                                    // ==========================================
                                    $history_data = [];

                                    // STEP 1: Pengajuan Disetujui
                                    $history_data[] = [
                                        'status' => 'Pengajuan Disetujui',
                                        'date' => date('d M Y H:i', strtotime($data['tanggal_pengajuan'])),
                                        'user' => 'Sistem / Admin',
                                        'icon' => 'fa-file-signature',
                                        'color' => 'info'
                                    ];

                                    // STEP 2: Input Fisik
                                    $q_input = mysqli_query($mysqli, "
                                        SELECT MAX(d.tgl_upload) as last_input 
                                        FROM tbl_dokumen d
                                        JOIN tbl_bantex b ON d.id_bantex = b.id
                                        JOIN tbl_box bx ON b.id_box = bx.id
                                        WHERE bx.id_pengajuan = '$id_pengajuan'
                                    ");
                                    $cek_input = mysqli_fetch_assoc($q_input);
                                    if (!empty($cek_input['last_input'])) {
                                        $history_data[] = [
                                            'status' => 'Input Dokumen & Fisik',
                                            'date' => date('d M Y H:i', strtotime($cek_input['last_input'])),
                                            'user' => 'Admin Divisi',
                                            'icon' => 'fa-keyboard',
                                            'color' => 'secondary'
                                        ];
                                    }

                                    // STEP 3: History Lainnya (Siap Kirim, To Send, Cancel)
                                    $q_hist = mysqli_query($mysqli, "
                                        SELECT h.waktu, h.status, h.keterangan 
                                        FROM tbl_history_pengiriman h
                                        JOIN tbl_pengiriman pg ON h.id_pengiriman = pg.id
                                        WHERE pg.id_pengajuan = '$id_pengajuan'
                                        ORDER BY h.waktu ASC
                                    ");

                                    if ($q_hist && mysqli_num_rows($q_hist) > 0) {
                                        while ($h = mysqli_fetch_assoc($q_hist)) {
                                            $stat_text = strtolower($h['status']);
                                            $h_icon = 'fa-clock';
                                            $h_color = 'primary';

                                            if (strpos($stat_text, 'send') !== false || strpos($stat_text, 'kirim') !== false) {
                                                $h_icon = 'fa-truck';
                                                $h_color = 'primary';
                                            } elseif (strpos($stat_text, 'cancel') !== false || strpos($stat_text, 'batal') !== false) {
                                                $h_icon = 'fa-times-circle';
                                                $h_color = 'danger';
                                            } elseif (strpos($stat_text, 'siap') !== false) {
                                                $h_icon = 'fa-box-open';
                                                $h_color = 'info';
                                            }

                                            $history_data[] = [
                                                'status' => strtoupper($h['status']),
                                                'date' => date('d M Y H:i', strtotime($h['waktu'])),
                                                'user' => 'Petugas Logistik',
                                                'icon' => $h_icon,
                                                'color' => $h_color
                                            ];
                                        }
                                    }

                                    usort($history_data, function ($a, $b) {
                                        return strtotime($b['date']) - strtotime($a['date']);
                                    });

                                    $dataObj = [
                                        'id_transaksi' => $id_trx,
                                        'divisi' => $data['nama_divisi'],
                                        'singkatan' => $data['singkatan_divisi'],
                                        'total_box' => $data['jumlah_box'],
                                        'jumlah' => $data['total_bantex'],
                                        'rf_id' => !empty($data['sample_rfid']) ? $data['sample_rfid'] . '...' : 'Belum discan',
                                        'history' => $history_data
                                    ];
                                    $jsonString = htmlspecialchars(json_encode($dataObj), ENT_QUOTES, 'UTF-8');
                                    ?>
                                    <tr>
                                        <td class="text-center text-muted font-weight-bold"><?= $no++; ?></td>
                                        <td><span class="badge-id"><?= $id_trx ?></span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-divisi mr-3"><?= $data['singkatan_divisi'] ?></div>
                                                <div>
                                                    <div class="font-weight-bold text-dark"><?= $data['nama_divisi'] ?></div>
                                                    <div class="small text-muted">
                                                        <?= date('d M Y', strtotime($data['tanggal_pengajuan'])); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="badge badge-light border">
                                                <?= $data['jumlah_box'] ?> Box | <?= $data['total_bantex'] ?> Bantex
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge-status-pill <?= $badge_class ?>" data-toggle="tooltip"
                                                title="<?= $tooltip_text ?>">
                                                <i class="fas <?= $icon ?>"></i> <?= $label ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-action-group">
                                                <button type="button" onclick="bukaModalLacak(<?= $jsonString ?>)"
                                                    class="btn-icon-soft btn-lacak" data-toggle="tooltip" title="Lacak">
                                                    <i class="fas fa-search-location"></i>
                                                </button>

                                                <button type="button"
                                                    onclick="bukaModalStatus('<?= $data['id'] ?>', '<?= $id_trx ?>', '<?= $db_status ?>')"
                                                    class="btn-icon-soft btn-edit" data-toggle="tooltip" title="Ubah Status">
                                                    <i class="fas fa-pen"></i>
                                                </button>
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

    <div class="modal fade" id="modalLacak" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0" style="border-radius: 15px; overflow: hidden;">
                <div class="modal-track-header">
                    <h5 class="mb-0 font-weight-bold"><i class="fas fa-map-marked-alt mr-2"></i> Tracking Dokumen</h5>
                    <button type="button" class="close text-white opacity-1" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-track-content">
                    <div class="text-center mb-4">
                        <span class="badge badge-warning text-white mb-2 px-3 py-1" id="lblIdTransaksi"
                            style="font-size: 12px; border-radius: 20px;">TRX-000</span>
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
                                <div class="track-label">SAMPLE RFID</div>
                                <div class="track-value val-green" id="lblRfid">-</div>
                            </div>
                        </div>
                    </div>

                    <div class="track-timeline">
                        <h6 class="font-weight-bold text-dark mb-3">Riwayat Status Realtime</h6>
                        <div id="timelineContainer"></div>
                    </div>
                </div>
                <div class="modal-footer bg-white border-top-0 justify-content-center pb-4">
                    <button type="button" class="btn btn-secondary btn-round px-5" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalStatus" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0">
                <div class="modal-header modal-header-status">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-edit mr-2"></i> Update Status Pengiriman</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body p-4">
                    <form id="formUpdateStatus">
                        <input type="hidden" name="id_pengajuan" id="status_id_pengajuan">
                        <div class="text-center mb-4">
                            <span class="badge badge-light border px-3 py-2" id="status_trx_display"
                                style="font-size: 14px; font-family: monospace;">TRX-000</span>
                            <p class="text-muted small mt-2">Pilih status terbaru untuk pengajuan ini.</p>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Pilih Status <span class="text-danger">*</span></label>

                            <div class="row px-2 mt-2">
                                <div class="col-12 mb-2">
                                    <label class="selectgroup-item w-100">
                                        <input type="radio" name="status_baru" value="Siap Kirim" class="selectgroup-input">
                                        <span class="selectgroup-button selectgroup-button-icon py-3"><i
                                                class="fas fa-box-open d-block mb-1"></i> SIAP KIRIM</span>
                                    </label>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="selectgroup-item w-100">
                                        <input type="radio" name="status_baru" value="To Send" class="selectgroup-input">
                                        <span class="selectgroup-button selectgroup-button-icon py-3"><i
                                                class="fas fa-truck d-block mb-1"></i> TO SEND</span>
                                    </label>
                                </div>
                                <div class="col-6 mb-2">
                                    <label class="selectgroup-item w-100">
                                        <input type="radio" name="status_baru" value="Cancel" class="selectgroup-input">
                                        <span class="selectgroup-button selectgroup-button-icon py-3"><i
                                                class="fas fa-times-circle d-block mb-1"></i> CANCEL</span>
                                    </label>
                                </div>
                            </div>

                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-warning btn-block font-weight-bold shadow">
                                <i class="fas fa-save mr-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () { $('[data-toggle="tooltip"]').tooltip(); });

        function bukaModalStatus(id, noTrx, currentStatus) {
            $('#status_id_pengajuan').val(id);
            $('#status_trx_display').text(noTrx);
            $('input[name="status_baru"]').prop('checked', false);

            // Konversi nilai status lama jika ada yang memencet record lawas
            if (currentStatus == 'Telah Dikirim') currentStatus = 'To Send';
            if (currentStatus == 'Dibatalkan') currentStatus = 'Cancel';

            if (currentStatus && currentStatus != 'Disetujui' && currentStatus != 'Diterima') {
                $('input[name="status_baru"][value="' + currentStatus + '"]').prop('checked', true);
            }
            $('#modalStatus').modal('show');
        }

        function bukaModalLacak(data) {
            $('#lblIdTransaksi').text(data.id_transaksi);
            $('#lblJudul').text('Status Pengiriman ' + data.singkatan);
            $('#lblDivisiName').text(data.divisi);

            $('#lblJmlBox').text(data.total_box);
            $('#lblJmlBantex').text(data.jumlah);
            $('#lblRfid').text(data.rf_id);

            let html = '';
            if (data.history && data.history.length > 0) {
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
                            <div class="t-user" style="font-size:12px; font-weight:bold; color:#64748b;">${item.user}</div>
                        </div>
                    </div>`;
                });
            } else {
                html = '<div class="text-center text-muted">Belum ada riwayat status.</div>';
            }
            $('#timelineContainer').html(html);
            $('#modalLacak').modal('show');
        }

        // AJAX Update Status
        $('#formUpdateStatus').on('submit', function (e) {
            e.preventDefault();

            let status = $('input[name="status_baru"]:checked').val();
            if (!status) {
                Swal.fire('Peringatan', 'Silakan pilih salah satu status.', 'warning');
                return;
            }

            Swal.fire({
                title: 'Update Status?',
                text: "Status akan diubah menjadi " + status.toUpperCase(),
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Update',
                confirmButtonColor: '#f59e0b'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'modules/pengiriman_box/proses_update_status.php',
                        type: 'POST',
                        data: $(this).serialize(),
                        dataType: 'json',
                        success: function (resp) {
                            if (resp.status === 'success') {
                                Swal.fire('Berhasil', resp.message, 'success').then(() => location.reload());
                            } else {
                                Swal.fire('Gagal', resp.message, 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('Error', 'Terjadi kesalahan server', 'error');
                        }
                    });
                }
            });
        });
    </script>
<?php } ?>