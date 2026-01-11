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
                                <th>Divisi & Tanggal</th>
                                <th class="text-center">Volume Arsip</th>
                                <th width="20%">Status</th>
                                <th class="text-center" width="15%">Aksi Approval</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Ambil Data Header (Pengajuan) + Hitung Jumlah Box
                            $query = mysqli_query($mysqli, "
                                SELECT 
                                    p.id AS id_pengajuan,
                                    p.tanggal_pengajuan,
                                    p.status,
                                    d.nama_divisi,
                                    d.singkatan_divisi,
                                    (SELECT COUNT(*) FROM tbl_box WHERE id_pengajuan = p.id) as total_box
                                FROM tbl_pengajuan p
                                INNER JOIN tbl_divisi d ON p.id_divisi = d.id
                                ORDER BY p.id DESC
                            ");

                            if (mysqli_num_rows($query) == 0) {
                                echo '<tr><td colspan="5" class="text-center py-5 text-muted">Belum ada pengajuan masuk.</td></tr>';
                            } else {
                                $no = 1;
                                while ($data = mysqli_fetch_assoc($query)) {
                                    $id      = $data['id_pengajuan'];
                                    $divisi  = $data['singkatan_divisi'] . " - " . $data['nama_divisi'];
                                    $tanggal = date('d M Y', strtotime($data['tanggal_pengajuan']));
                                    
                                    // LOGIKA VOLUME
                                    $box     = $data['total_box'];
                                    $bantex  = $box * 6; // Rumus: 1 Box = 6 Bantex
                                    
                                    $status  = $data['status'];
                            ?>
                                    <tr>
                                        <td class="text-center text-muted"><?= $no++; ?></td>
                                        
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
                                            <?php } elseif ($status == 'Diterima') { ?>
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
                    window.location.href = "modules/barang-masuk/proses_approval.php?id=" + id + "&aksi=" + aksi;
                }
            });
        }
    </script>
<?php } ?>