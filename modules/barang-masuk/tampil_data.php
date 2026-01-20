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
                                <th width="20%">ID Transaksi</th> 
                                <th>Divisi & Tanggal</th>
                                <th class="text-center">Volume Arsip</th>
                                <th width="15%">Status Barang</th>
                                <th class="text-center" width="15%">Aksi Approval</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // --- DATA DUMMY UPDATE (FORMAT ID BARU) ---
                            // Format: KODE-YYMMDD-NO (Cth: DAPN-260113-00001)
                            $data_dummy = [
                                [
                                    'id' => 1,
                                    'id_transaksi' => 'DAPN-260113-00001', // Updated
                                    'divisi' => 'DAPN - Divisi Akuntansi & Perpajakan',
                                    'tanggal' => '2026-01-13',
                                    'total_box' => 1,
                                    'status' => 'Diterima' 
                                ],
                                [
                                    'id' => 2,
                                    'id_transaksi' => 'DTIS-260113-00001', // Updated (DTI -> DTIS)
                                    'divisi' => 'DTIS - Divisi Teknologi Informasi',
                                    'tanggal' => '2026-01-13',
                                    'total_box' => 3,
                                    'status' => 'Pending' 
                                ],
                                [
                                    'id' => 3,
                                    'id_transaksi' => 'DAPN-260113-00002', // Updated (Urutan ke-2 hari yg sama)
                                    'divisi' => 'DAPN - Divisi Akuntansi & Perpajakan',
                                    'tanggal' => '2026-01-13',
                                    'total_box' => 2,
                                    'status' => 'Pending' 
                                ],
                                [
                                    'id' => 4,
                                    'id_transaksi' => 'DPSR-260112-00001', // Updated (Tgl 12 Jan 26 -> 260112)
                                    'divisi' => 'DPSR - Divisi PSR dan Plasma',
                                    'tanggal' => '2026-01-12',
                                    'total_box' => 1,
                                    'status' => 'Diterima'
                                ],
                                [
                                    'id' => 5,
                                    'id_transaksi' => 'DSMS-251224-00001', // Updated (Tgl 24 Des 25 -> 251224)
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
                                    $bantex   = $box * 6; 
                                    $status   = $data['status'];
                            ?>
                                    <tr>
                                        <td class="text-center text-muted"><?= $no++; ?></td>
                                        
                                        <td>
                                            <span class="badge badge-light border text-dark font-weight-bold" style="font-family: monospace; font-size: 13px; letter-spacing: 0.5px;">
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
                                                <span class="badge badge-warning text-white shadow-sm" style="font-size: 11px;">
                                                    <i class="fas fa-truck-loading mr-1"></i> Menunggu Box/Bantek
                                                </span>
                                            <?php } elseif ($status == 'Diterima') { ?>
                                                <span class="badge badge-success shadow-sm" style="font-size: 11px;">
                                                    <i class="fas fa-box-open mr-1"></i> Box/Bantek Diterima
                                                </span>
                                            <?php } else { ?>
                                                <span class="badge badge-danger shadow-sm" style="font-size: 11px;">
                                                    <i class="fas fa-times-circle mr-1"></i> Ditolak
                                                </span>
                                            <?php } ?>
                                        </td>

                                        <td class="text-center"> 
                                            <?php if ($status == 'Pending') { ?>
                                                <button onclick="prosesApproval(<?= $id ?>, 'terima')" class="btn btn-success btn-sm btn-round shadow-sm mr-1" data-toggle="tooltip" title="Terima Barang (ACC)">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                
                                                <button onclick="prosesApproval(<?= $id ?>, 'tolak')" class="btn btn-danger btn-sm btn-round shadow-sm" data-toggle="tooltip" title="Tolak / Kembalikan">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            <?php } elseif ($status == 'Diterima') { ?>
                                                <small class="text-muted font-weight-bold"><i class="fas fa-check-circle text-success"></i> Selesai</small>
                                            <?php } else { ?>
                                                <small class="text-muted">Closed</small>
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

        // LOGIKA APPROVAL DENGAN SWEETALERT
        function prosesApproval(id, aksi) {
            let judul, pesan, icon, tombol;

            if (aksi === 'terima') {
                judul = "Terima Barang?";
                pesan = "Pastikan fisik box dan bantex sudah diterima di gudang sesuai data.";
                icon = "info"; // Ikon Info/Success
                tombol = "Ya, Barang Diterima";
            } else {
                judul = "Tolak Pengajuan?";
                pesan = "Pengajuan akan dikembalikan ke user untuk diperbaiki.";
                icon = "warning"; // Ikon Warning
                tombol = "Ya, Tolak";
            }

            swal({
                title: judul,
                text: pesan,
                icon: icon,
                buttons: {
                    cancel: {
                        text: "Batal",
                        value: null,
                        visible: true,
                        className: "btn btn-secondary",
                        closeModal: true,
                    },
                    confirm: {
                        text: tombol,
                        value: true,
                        visible: true,
                        className: aksi === 'terima' ? "btn btn-success" : "btn btn-danger",
                        closeModal: false
                    }
                }
            })
            .then((willProses) => {
                if (willProses) {
                    // Simulasi Loading
                    swal({
                        title: "Memproses...",
                        text: "Mohon tunggu sebentar",
                        icon: "info",
                        buttons: false,
                        closeOnClickOutside: false,
                    });

                    // Redirect ke proses PHP (Ganti URL sesuai file backend Anda)
                    // Contoh: modules/barang-masuk/proses_approval.php?id=...
                    setTimeout(function() {
                        window.location.href = "modules/barang-masuk/proses_approval.php?id=" + id + "&aksi=" + aksi;
                    }, 1000);
                }
            });
        }
    </script>
<?php } ?>