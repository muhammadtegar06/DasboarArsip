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
            width: 100px;
            justify-content: center;
        }

        /* Warna Status */
        .st-siap {
            background: #e0f2fe;
            color: #0284c7;
            border-color: #bae6fd;
        }

        /* Biru Muda */
        .st-kirim {
            background: #dcfce7;
            color: #15803d;
            border-color: #bbf7d0;
        }

        /* Hijau */
        .st-batal {
            background: #fee2e2;
            color: #b91c1c;
            border-color: #fecaca;
        }

        /* Merah */
        .st-default {
            background: #f3f4f6;
            color: #374151;
            border-color: #e5e7eb;
        }

        /* Abu */

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

        /* Modal Style */
        .modal-header-status {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            border: none;
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
                            // QUERY DATABASE
                            // Menampilkan data yang SUDAH SELESAI INPUT RFID (siap kirim) ATAU statusnya sudah diganti manual
                            $query = mysqli_query($mysqli, "
                                SELECT 
                                    p.id, p.no_pengajuan, p.tanggal_pengajuan, p.jumlah_box, p.status,
                                    d.nama_divisi, d.singkatan_divisi,
                                    (SELECT COUNT(*) FROM tbl_bantex b JOIN tbl_box bx ON b.id_box = bx.id WHERE bx.id_pengajuan = p.id) as total_bantex
                                FROM tbl_pengajuan p
                                JOIN tbl_divisi d ON p.id_divisi = d.id
                                WHERE 
                                    -- KONDISI 1: Statusnya memang sudah update (Telah Dikirim / Dibatalkan)
                                    p.status IN ('Telah Dikirim', 'Dibatalkan', 'Siap Kirim')
                                    OR
                                    -- KONDISI 2: Masih status 'Disetujui'/'Diterima' TAPI semua RFID sudah diisi (Otomatis masuk 'Siap Kirim')
                                    (
                                        (p.status = 'Disetujui' OR p.status = 'Diterima')
                                        AND
                                        (SELECT COUNT(*) FROM tbl_box bx2 WHERE bx2.id_pengajuan = p.id AND (bx2.rfid_code IS NULL OR bx2.rfid_code = '')) = 0
                                    )
                                ORDER BY p.id DESC
                            ");

                            $no = 1;
                            while ($data = mysqli_fetch_assoc($query)) {
                                $id_trx = $data['no_pengajuan'];

                                // Tentukan Label Status & Warna
                                $db_status = $data['status'];
                                $badge_class = 'st-default';
                                $icon = 'fa-question';
                                $label = $db_status;

                                // Logika Tampilan Badge
                                if ($db_status == 'Telah Dikirim') {
                                    $badge_class = 'st-kirim';
                                    $icon = 'fa-check-double';
                                    $label = 'TERKIRIM';
                                } elseif ($db_status == 'Dibatalkan') {
                                    $badge_class = 'st-batal';
                                    $icon = 'fa-times-circle';
                                    $label = 'DIBATALKAN';
                                } elseif ($db_status == 'Siap Kirim' || $db_status == 'Disetujui' || $db_status == 'Diterima') {
                                    $badge_class = 'st-siap';
                                    $icon = 'fa-box-open';
                                    $label = 'SIAP KIRIM';
                                    // Normalisasi status DB agar pas di Modal
                                    $db_status = 'Siap Kirim';
                                }
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
                                        <span class="badge-status-pill <?= $badge_class ?>">
                                            <i class="fas <?= $icon ?>"></i> <?= $label ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-action-group">
                                            <button type="button" class="btn-icon-soft btn-lacak" data-toggle="tooltip"
                                                title="Lacak">
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
                            <?php } ?>
                        </tbody>
                    </table>
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
                            <div class="selectgroup w-100">
                                <label class="selectgroup-item">
                                    <input type="radio" name="status_baru" value="Siap Kirim" class="selectgroup-input">
                                    <span class="selectgroup-button selectgroup-button-icon"><i
                                            class="fas fa-box-open mr-2"></i> SIAP KIRIM</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="status_baru" value="Telah Dikirim" class="selectgroup-input">
                                    <span class="selectgroup-button selectgroup-button-icon"><i
                                            class="fas fa-shipping-fast mr-2"></i> TELAH DIKIRIM</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="status_baru" value="Dibatalkan" class="selectgroup-input">
                                    <span class="selectgroup-button selectgroup-button-icon"><i
                                            class="fas fa-times-circle mr-2"></i> DIBATALKAN</span>
                                </label>
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

        // 1. Buka Modal Status
        function bukaModalStatus(id, noTrx, currentStatus) {
            $('#status_id_pengajuan').val(id);
            $('#status_trx_display').text(noTrx);

            // Reset Radio Button
            $('input[name="status_baru"]').prop('checked', false);

            // Centang status saat ini
            if (currentStatus) {
                $('input[name="status_baru"][value="' + currentStatus + '"]').prop('checked', true);
            }

            $('#modalStatus').modal('show');
        }

        // 2. Proses Simpan Status (AJAX)
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