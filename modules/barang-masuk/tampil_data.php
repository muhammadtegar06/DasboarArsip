<?php
// Mencegah akses langsung
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('location: 404.html');
} else {
?>
    <div class="panel-header bg-secondary-gradient">
        <div class="page-inner py-45">
            <div class="d-flex align-items-left align-items-md-top flex-column flex-md-row">
                <div class="page-header text-white">
                    <h4 class="page-title text-white"><i class="fas fa-check-double mr-2"></i> Approval Arsip Masuk</h4>
                </div>
                <div class="ml-md-auto py-2 py-md-0">
                    <a href="?module=form_entri_barang_masuk" class="btn btn-white btn-border btn-round mr-2">
                        <i class="fa fa-plus mr-2"></i> Buat Pengajuan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-inner mt--5">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom-0 pt-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title font-weight-bold text-dark">Daftar Pengajuan Box</h4>
                </div>
            </div>
            <div class="card-body px-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center" width="5%">#</th>
                                <th width="15%">ID Transaksi</th> <th>Divisi & Tanggal</th>
                                <th class="text-center">Volume Arsip</th>
                                <th width="15%">Status</th>
                                <th class="text-center" width="15%">Aksi Approval</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // --- DATA DUMMY (PENGGANTI DATABASE SEMENTARA) ---
                            $data_dummy = [
                                [
                                    'id' => 1,
                                    'id_transaksi' => 'TRX-2026-001',
                                    'divisi' => 'DAPN - Divisi Akuntansi & Perpajakan',
                                    'tanggal' => '2026-01-13',
                                    'total_box' => 1,
                                    'status' => 'Disetujui'
                                ],
                                [
                                    'id' => 2,
                                    'id_transaksi' => 'TRX-2026-002',
                                    'divisi' => 'DTI - Divisi Teknologi Informasi',
                                    'tanggal' => '2026-01-13',
                                    'total_box' => 3,
                                    'status' => 'Pending'
                                ],
                                [
                                    'id' => 3,
                                    'id_transaksi' => 'TRX-2026-003',
                                    'divisi' => 'DAPN - Divisi Akuntansi & Perpajakan',
                                    'tanggal' => '2026-01-13',
                                    'total_box' => 2,
                                    'status' => 'Pending'
                                ],
                                [
                                    'id' => 4,
                                    'id_transaksi' => 'TRX-2026-004',
                                    'divisi' => 'DPSR - Divisi PSR dan Plasma',
                                    'tanggal' => '2026-01-12',
                                    'total_box' => 1,
                                    'status' => 'Disetujui'
                                ],
                                [
                                    'id' => 5,
                                    'id_transaksi' => 'TRX-2025-099',
                                    'divisi' => 'DSMS - Divisi Sistem Manajemen',
                                    'tanggal' => '2025-12-24',
                                    'total_box' => 1,
                                    'status' => 'Ditolak'
                                ]
                            ];

                            if (empty($data_dummy)) {
                                echo '<tr><td colspan="6" class="text-center py-5 text-muted">Belum ada pengajuan masuk.</td></tr>';
                            } else {
                                $no = 1;
                                foreach ($data_dummy as $data) {
                                    $id       = $data['id'];
                                    $id_trx   = $data['id_transaksi'];
                                    $divisi   = $data['divisi'];
                                    $tanggal  = date('d M Y', strtotime($data['tanggal']));
                                    
                                    // LOGIKA VOLUME
                                    $box      = $data['total_box'];
                                    $bantex   = $box * 6; // Rumus: 1 Box = 6 Bantex
                                    $status   = $data['status'];
                            ?>
                                    <tr>
                                        <td class="text-center text-muted"><?= $no++; ?></td>
                                        
                                        <td>
                                            <span class="badge badge-light border text-dark font-weight-bold" style="font-family: monospace; font-size: 13px;">
                                                <?= $id_trx ?>
                                            </span>
                                        </td>

                                        <td>
                                            <div class="font-weight-bold text-dark"><?= $divisi ?></div>
                                            <div class="small text-muted"><i class="far fa-calendar-alt mr-1"></i> <?= $tanggal ?></div>
                                        </td>

                                        <td class="text-center">
                                            <h5 class="mb-0 font-weight-bold text-dark"><?= $box ?> Box</h5>
                                            <small class="text-muted text-primary font-weight-bold">(Estimasi <?= $bantex ?> Bantex)</small>
                                        </td>

                                        <td>
                                            <?php if ($status == 'Pending') { ?>
                                                <span class="badge badge-warning text-white"><i class="fas fa-clock mr-1"></i> Menunggu Approval</span>
                                            <?php } elseif ($status == 'Disetujui') { ?>
                                                <span class="badge badge-success"><i class="fas fa-check mr-1"></i> Disetujui</span>
                                            <?php } else { ?>
                                                <span class="badge badge-danger"><i class="fas fa-times mr-1"></i> Ditolak</span>
                                            <?php } ?>
                                        </td>

                                        <td class="text-center"> 
                                            <?php if ($status == 'Pending') { ?>
                                                <button onclick="prosesApproval(<?= $id ?>, 'terima')" class="btn btn-success btn-sm btn-round shadow-sm mr-1" data-toggle="tooltip" title="Terima Pengajuan">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                
                                                <button onclick="prosesApproval(<?= $id ?>, 'tolak')" class="btn btn-danger btn-sm btn-round shadow-sm" data-toggle="tooltip" title="Tolak Pengajuan">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            <?php } else { ?>
                                                <small class="text-muted"><i class="fas fa-check-circle text-success"></i> Selesai</small>
                                            <?php } ?>
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

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });

        function prosesApproval(id, aksi) {
            let pesan = aksi === 'terima' ? "Pengajuan akan disetujui." : "Pengajuan akan ditolak.";
            let warna = aksi === 'terima' ? "success" : "warning";

            swal({
                title: "Konfirmasi " + aksi.toUpperCase() + "?",
                text: pesan,
                icon: warna,
                buttons: ["Batal", "Ya, Lanjutkan"],
            })
            .then((willProses) => {
                if (willProses) {
                    // Redirect simulasi (karena dummy, ini tidak akan mengubah data asli)
                    window.location.href = "modules/barang-masuk/proses_approval.php?id=" + id + "&aksi=" + aksi;
                }
            });
        }
    </script>
<?php } ?>