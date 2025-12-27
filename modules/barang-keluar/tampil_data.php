<?php
// Tampil Data Barang Masuk (Versi Repository Arsip - Final UI)
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('location: 404.html');
}
else {
    // Menampilkan Notifikasi Pesan
    if (isset($_GET['pesan'])) {
        $alert_type = "alert-success";
        $alert_icon = "fa-check";
        $alert_title = "Sukses!";
        $alert_msg = "";

        if ($_GET['pesan'] == 1) $alert_msg = "Data arsip berhasil disimpan.";
        elseif ($_GET['pesan'] == 2) $alert_msg = "Data arsip berhasil dihapus.";
        elseif ($_GET['pesan'] == 3) $alert_msg = "Status berhasil di-ACC.";
        elseif ($_GET['pesan'] == 4) {
             $alert_type = "alert-danger";
             $alert_icon = "fa-times";
             $alert_title = "Ditolak!";
             $alert_msg = "Pengajuan telah ditolak.";
        }

        echo '<div class="alert alert-notify '.$alert_type.' alert-dismissible fade show" role="alert">
                <span data-notify="icon" class="fas '.$alert_icon.'"></span> 
                <span data-notify="title" class="'.$alert_type.'">'.$alert_title.'</span> 
                <span data-notify="message">'.$alert_msg.'</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              </div>';
    }
?>
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-4">
            <div class="d-flex align-items-left align-items-md-top flex-column flex-md-row">
                <div class="page-header text-white">
                    <h4 class="page-title text-white"><i class="fas fa-archive mr-2"></i> Repository Arsip</h4>
                    <ul class="breadcrumbs">
                        <li class="nav-home"><a href="?module=dashboard"><i class="flaticon-home text-white"></i></a></li>
                        <li class="separator"><i class="flaticon-right-arrow"></i></li>
                        <li class="nav-item"><a>Data Box</a></li>
                    </ul>
                </div>
                <div class="ml-md-auto py-2 py-md-0">
                    <a href="?module=form_entri_barang_masuk" class="btn btn-secondary btn-round">
                        <span class="btn-label"><i class="fa fa-plus mr-2"></i></span> Input Arsip Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-inner mt--5">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Data Box & Bantex Divisi</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="display table table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">Divisi</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Total Box</th>
                                <th class="text-center">RF ID</th>
                                <th class="text-center">Total Bantex</th>
                                <th class="text-center" style="width: 180px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $query = mysqli_query($mysqli, "SELECT * FROM tbl_barang_masuk ORDER BY id_transaksi DESC");

                            while ($data = mysqli_fetch_assoc($query)) { 
                                $id_transaksi = $data['id_transaksi'];
                                $divisi       = $data['divisi'];
                                $tanggal      = date('d-m-Y', strtotime($data['tanggal']));
                                $total_box    = isset($data['total_box']) ? $data['total_box'] : 0;
                                $rf_id        = isset($data['rf_id']) ? $data['rf_id'] : '-';
                                $total_bantex = isset($data['jumlah']) ? $data['jumlah'] : 0;
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td class="text-center font-weight-bold text-primary"><?php echo $divisi; ?></td>
                                    <td class="text-center"><?php echo $tanggal; ?></td>
                                    <td class="text-center">
                                        <span class="badge badge-count border border-secondary text-secondary" style="background: #f1f1f1;">
                                            <i class="fas fa-box mr-1"></i> <?php echo $total_box; ?> Box
                                        </span>
                                    </td>
                                    <td class="text-center text-muted"><?php echo $rf_id; ?></td>
                                    <td class="text-center">
                                        <span class="badge badge-info">
                                            <i class="fas fa-layer-group mr-1"></i> <?php echo $total_bantex; ?> Bantex
                                        </span>
                                    </td>
                                    
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm shadow-sm p-1" style="background: #fff; border-radius: 50px; border: 1px solid #e8e8e8;">
                                            
                                            <button type="button" onclick="modalAcc('<?php echo $id_transaksi; ?>')" class="btn btn-icon btn-round btn-success btn-xs mr-1" data-toggle="tooltip" title="ACC / Accept">
                                                <i class="fas fa-check"></i>
                                            </button>

                                            <a href="?module=form_entri_barang_masuk&id=<?php echo $id_transaksi; ?>" class="btn btn-icon btn-round btn-secondary btn-xs mr-1" data-toggle="tooltip" title="Edit">
                                                <i class="fas fa-pen"></i>
                                            </a>

                                            <button type="button" onclick="modalReject('<?php echo $id_transaksi; ?>')" class="btn btn-icon btn-round btn-danger btn-xs mr-1" data-toggle="tooltip" title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>

                                            <button type="button" onclick="lihatDetail('<?php echo $id_transaksi; ?>', '<?php echo $divisi; ?>')" class="btn btn-icon btn-round btn-info btn-xs" data-toggle="tooltip" title="Lihat Isi Box">
                                                <i class="fas fa-eye"></i>
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

    <div class="modal fade" id="modalAcc" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title font-weight-bold">Konfirmasi ACC</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                    <p class="lead">Pilih tindakan untuk menyimpan status ACC:</p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">Batal</button>
                    <a href="#" id="btnSimpanOnly" class="btn btn-success btn-round">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </a>
                    <a href="#" id="btnSimpanCetak" target="_blank" class="btn btn-primary btn-round">
                        <i class="fas fa-print mr-1"></i> Simpan & Cetak
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalReject" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Tolak Pengajuan (Reject)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="modules/barang-masuk/proses_reject.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="id_transaksi" id="rejectId">
                        <div class="form-group">
                            <label for="catatan" class="font-weight-bold">Catatan Penolakan <span class="text-danger">*</span></label>
                            <div class="border rounded p-2 bg-light">
                                <div class="mb-2 border-bottom pb-1">
                                    <button type="button" class="btn btn-sm btn-icon btn-link text-dark"><i class="fas fa-bold"></i></button>
                                    <button type="button" class="btn btn-sm btn-icon btn-link text-dark"><i class="fas fa-italic"></i></button>
                                    <button type="button" class="btn btn-sm btn-icon btn-link text-dark"><i class="fas fa-list-ul"></i></button>
                                </div>
                                <textarea class="form-control border-0 bg-light" name="catatan" id="catatan" rows="5" placeholder="Tuliskan alasan..." required style="resize: none;"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-round">Submit</button>
                        <button type="button" class="btn btn-default border btn-round" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDetail" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="judulDetail">Detail Arsip</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="background-color: #f9f9f9;">
                    <div id="kontenDetail"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Modal ACC
        function modalAcc(id) {
            $('#btnSimpanOnly').attr('href', 'modules/barang-masuk/proses_acc.php?id=' + id + '&act=simpan');
            $('#btnSimpanCetak').attr('href', 'modules/barang-masuk/proses_acc.php?id=' + id + '&act=cetak');
            $('#btnSimpanCetak').off('click').click(function() {
                setTimeout(function(){ location.reload(); }, 1000); 
            });
            $('#modalAcc').modal('show');
        }

        // Modal Reject
        function modalReject(id) {
            $('#rejectId').val(id);
            $('#catatan').val('');
            $('#modalReject').modal('show');
        }

        // FUNGSI LOGIKA LIHAT (Box -> Bantex -> Dokumen)
        function lihatDetail(id, divisi) {
            $('#judulDetail').text("Detail Arsip - " + divisi);
            
            // --- SIMULASI DATA ---
            // Nanti bagian ini diganti dengan Ajax ke database.
            // Konsepnya: Loop Box -> Loop Bantex -> Loop Dokumen
            
            let html = '';

            // BOX 1 (Simulasi)
            html += `
            <div class="card mb-3 shadow-sm">
                <div class="card-header font-weight-bold bg-white text-primary">
                    <i class="fas fa-box-open"></i> Box 1
                </div>
                <div class="card-body p-0">
                    <div class="accordion" id="accordionBox1">
                        
                        <div class="card mb-0 border-0">
                            <div class="card-header" id="headingOne">
                                <h5 class="mb-0">
                                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne">
                                        <i class="fas fa-folder text-warning mr-2"></i> Bantex: Kontrak 2024
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseOne" class="collapse show" data-parent="#accordionBox1">
                                <div class="card-body bg-light pl-5">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item bg-transparent border-0 py-1"><i class="far fa-file-pdf text-danger mr-2"></i> SPK No. 001/2024 (Januari 2024)</li>
                                        <li class="list-group-item bg-transparent border-0 py-1"><i class="far fa-file-pdf text-danger mr-2"></i> Invoice Tagihan Termin 1</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-0 border-0">
                            <div class="card-header" id="headingTwo">
                                <h5 class="mb-0">
                                    <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo">
                                        <i class="fas fa-folder text-warning mr-2"></i> Bantex: Laporan Bulanan
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseTwo" class="collapse" data-parent="#accordionBox1">
                                <div class="card-body bg-light pl-5">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item bg-transparent border-0 py-1"><i class="far fa-file-alt text-info mr-2"></i> Laporan Operasional Jan</li>
                                        <li class="list-group-item bg-transparent border-0 py-1"><i class="far fa-file-alt text-info mr-2"></i> Laporan Operasional Feb</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>`;

            // Masukkan HTML yang sudah disusun ke dalam Modal
            $('#kontenDetail').html(html);
            $('#modalDetail').modal('show');
        }
    </script>
<?php } ?>