<?php
// Tampil Data Barang Masuk (Mode: Data Dummy & Link ke Form Entri)
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('location: 404.html');
}
else {
    // --- 1. MEMBUAT DATA DUMMY (Simulasi) ---
    $data_dummy = [
        [
            'id_transaksi' => 2025001,
            'divisi'       => 'DTIS - Divisi Teknologi Informasi',
            'tanggal'      => '2025-12-30',
            'total_box'    => 5,
            'jumlah'       => 30, 
            'status'       => 'Diterima' 
        ],
        [
            'id_transaksi' => 2025002,
            'divisi'       => 'DSDM - Divisi Sumber Daya Manusia',
            'tanggal'      => '2025-12-28',
            'total_box'    => 2,
            'jumlah'       => 12,
            'status'       => 'Pending'
        ],
        [
            'id_transaksi' => 2025003,
            'divisi'       => 'DHKM - Divisi Hukum & Legal',
            'tanggal'      => '2025-12-25',
            'total_box'    => 10,
            'jumlah'       => 60,
            'status'       => 'Diterima'
        ],
        [
            'id_transaksi' => 2025004,
            'divisi'       => 'DKEU - Divisi Keuangan',
            'tanggal'      => '2025-12-20',
            'total_box'    => 3,
            'jumlah'       => 18,
            'status'       => 'Ditolak' 
        ],
        [
            'id_transaksi' => 2025005,
            'divisi'       => 'DPSR - Divisi PSR dan Plasma',
            'tanggal'      => '2025-12-15',
            'total_box'    => 1,
            'jumlah'       => 6,
            'status'       => 'Diterima'
        ]
    ];
?>
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-4">
            <div class="d-flex align-items-left align-items-md-top flex-column flex-md-row">
                <div class="page-header text-white">
                    <h4 class="page-title text-white"><i class="fas fa-archive mr-2"></i> Repository Arsip</h4>
                </div>
                <div class="ml-md-auto py-2 py-md-0">
                    <a href="?module=form_entri_barang_masuk" class="btn btn-secondary btn-round">
                        <span class="btn-label"><i class="fa fa-plus mr-2"></i></span> Pengajuan Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-inner mt--5">
        <div class="card shadow-sm">
            <div class="card-header">
                <div class="card-title font-weight-bold">Daftar Box & Bantex (Mode Preview)</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="display table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center">No.</th>
                                <th>Informasi Divisi & Tanggal</th>
                                <th class="text-center">Volume Arsip</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi / Input Dokumen</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            // Loop Data Dummy
                            foreach ($data_dummy as $data) { 
                                $id_transaksi = $data['id_transaksi'];
                                $divisi       = $data['divisi'];
                                $tanggal      = date('d M Y', strtotime($data['tanggal']));
                                $total_box    = $data['total_box'];
                                $total_bantex = $data['jumlah'];
                                $status       = $data['status']; 
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    
                                    <td>
                                        <div class="font-weight-bold text-dark" style="font-size: 14px;"><?php echo $divisi; ?></div>
                                        <div class="small text-muted mt-1">
                                            <i class="far fa-calendar-alt mr-1"></i> Diajukan: <?php echo $tanggal; ?>
                                        </div>
                                    </td>
                                    
                                    <td class="text-center">
                                        <h5 class="mb-0 font-weight-bold text-dark"><?php echo $total_box; ?> Box</h5>
                                        <small class="text-muted">(Total <?php echo $total_bantex; ?> Bantex)</small>
                                    </td>
                                    
                                    <td class="text-center">
                                        <?php if($status == 'Diterima') { ?>
                                            <span class="badge badge-success shadow-sm px-3 py-2">
                                                <i class="fas fa-check-circle mr-1"></i> Diterima
                                            </span>
                                        <?php } elseif($status == 'Pending') { ?>
                                            <span class="badge badge-warning text-white shadow-sm px-3 py-2">
                                                <i class="fas fa-clock mr-1"></i> Pending
                                            </span>
                                        <?php } else { ?>
                                            <span class="badge badge-danger shadow-sm px-3 py-2">
                                                <i class="fas fa-times-circle mr-1"></i> Ditolak
                                            </span>
                                        <?php } ?>
                                    </td>
                                    
                                    <td class="text-center">
                                        <?php if($status == 'Diterima') { ?>
                                            
                                            <a href="?module=form_entri&id=<?= $id_transaksi ?>&divisi=<?= urlencode($divisi) ?>&total_bantex=<?= $total_bantex ?>" 
                                               class="btn btn-primary btn-round btn-sm shadow font-weight-bold"
                                               data-toggle="tooltip" title="Klik untuk input dokumen">
                                                <i class="fas fa-file-import mr-2"></i> Input Dokumen
                                            </a>

                                        <?php } elseif ($status == 'Pending') { ?>
                                            <button type="button" class="btn btn-link text-muted btn-sm" style="cursor: default; text-decoration: none;">
                                                <i class="fas fa-lock mr-1"></i> Menunggu ACC
                                            </button>
                                        <?php } else { ?>
                                             <button type="button" class="btn btn-link text-danger btn-sm" style="cursor: default; text-decoration: none;">
                                                <i class="fas fa-ban mr-1"></i> Ditolak
                                            </button>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php } ?>