<?php
// Tampil Data Barang Masuk (Versi Final: Fitur Lengkap)
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('location: 404.html');
}
else {
    // Notifikasi Pesan
    if (isset($_GET['pesan'])) {
        $alert_type = "alert-success";
        $alert_msg = "";

        if ($_GET['pesan'] == 1) $alert_msg = "Data berhasil disubmit dan disimpan.";
        elseif ($_GET['pesan'] == 2) $alert_msg = "Data berhasil dihapus.";

        if (!empty($alert_msg)) {
            echo '<div class="alert alert-notify '.$alert_type.' alert-dismissible fade show" role="alert">
                    <span data-notify="icon" class="fas fa-check"></span> 
                    <span data-notify="title" class="text-success">Sukses!</span> 
                    <span data-notify="message">'.$alert_msg.'</span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  </div>';
        }
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
                <div class="card-title">Data Pengajuan Arsip</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="display table table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">Divisi</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Jumlah Box</th>
                                <th class="text-center">Jumlah Bantex</th>
                                <th class="text-center">Action</th>
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
                                $total_bantex = isset($data['jumlah']) ? $data['jumlah'] : 0;
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td class="text-center font-weight-bold text-primary"><?php echo $divisi; ?></td>
                                    <td class="text-center"><?php echo $tanggal; ?></td>
                                    
                                    <td class="text-center">
                                        <a href="javascript:void(0)" onclick="tampilDetailBox('<?php echo $total_box; ?>')" class="btn btn-sm btn-outline-secondary btn-round">
                                            <i class="fas fa-box mr-1"></i> <b><?php echo $total_box; ?></b> Box
                                        </a>
                                    </td>
                                    
                                    <td class="text-center">
                                        <a href="javascript:void(0)" onclick="tampilDetailBantex('<?php echo $id_transaksi; ?>', '<?php echo $total_bantex; ?>')" class="btn btn-sm btn-outline-info btn-round">
                                            <i class="fas fa-layer-group mr-1"></i> <b><?php echo $total_bantex; ?></b> Bantex
                                        </a>
                                    </td>
                                    
                                    <td class="text-center">
                                        <button type="button" 
                                            onclick="reviewSubmit('<?php echo $id_transaksi; ?>', '<?php echo $divisi; ?>', '<?php echo $total_box; ?>', '<?php echo $total_bantex; ?>')" 
                                            class="btn btn-success btn-round btn-sm shadow-sm">
                                            <i class="fas fa-paper-plane mr-1"></i> Submit
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

    <div class="modal fade" id="modalDetailBox" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-secondary">
                    <h5 class="modal-title text-white"><i class="fas fa-box mr-2"></i> Detail Box & RF ID</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="thead-light">
                                <tr><th>Nama Box</th><th>Nomor RF ID</th></tr>
                            </thead>
                            <tbody id="kontenTabelBox"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDetailBantex" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-white"><i class="fas fa-layer-group mr-2"></i> Detail Bantex & Dokumen</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body bg-light">
                    <div class="accordion" id="accordionBantex">
                        <div id="kontenAccordionBantex"></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Tutup</button></div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalReview" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title text-white font-weight-bold">
                        <i class="fas fa-clipboard-check mr-2"></i> Review & Konfirmasi
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="background-color: #f8f9fa;">
                    
                    <div class="alert alert-info shadow-sm">
                        <i class="fas fa-info-circle mr-2"></i> Silakan periksa kelengkapan arsip sebelum melakukan Submit.
                    </div>

                    <h5 class="font-weight-bold text-dark mb-3">Divisi: <span id="reviewDivisi"></span></h5>

                    <div id="kontenReviewLengkap"></div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">Batal</button>
                    <div>
                        <a href="#" id="btnEditData" class="btn btn-warning btn-round mr-2 text-white">
                            <i class="fas fa-pen mr-1"></i> Edit Data
                        </a>
                        <a href="#" id="btnFinalSubmit" class="btn btn-success btn-round px-4 shadow">
                            <i class="fas fa-paper-plane mr-1"></i> Ya, Submit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // --- 1. Detail Box (Klik Angka Box) ---
        function tampilDetailBox(jumlah_box) {
            let html = '';
            for (let i = 1; i <= jumlah_box; i++) {
                let rfid = "RF-" + Math.floor(100000 + Math.random() * 900000); 
                html += `<tr><td>Box ${i}</td><td><span class="badge badge-light border border-secondary">${rfid}</span></td></tr>`;
            }
            $('#kontenTabelBox').html(html);
            $('#modalDetailBox').modal('show');
        }

        // --- 2. Detail Bantex (Klik Angka Bantex - ACCORDION RESTORED) ---
        function tampilDetailBantex(id_transaksi, jumlah_bantex) {
            let html = '';

            // Looping membuat Accordion Bantex
            for (let i = 1; i <= jumlah_bantex; i++) {
                let idCollapse = "detailBantexCollapse" + i;
                let idHeading = "detailBantexHeading" + i;
                
                html += `
                <div class="card mb-2 border">
                    <div class="card-header bg-white p-0" id="${idHeading}">
                        <h5 class="mb-0">
                            <button class="btn btn-link btn-block text-left d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#${idCollapse}" aria-expanded="false">
                                <span><i class="fas fa-folder text-info mr-2"></i> Bantex ${i}: Dokumen Umum</span>
                                <i class="fas fa-chevron-down text-muted small"></i>
                            </button>
                        </h5>
                    </div>

                    <div id="${idCollapse}" class="collapse" aria-labelledby="${idHeading}" data-parent="#accordionBantex">
                        <div class="card-body bg-light pl-5 py-2">
                            <h6 class="text-muted font-weight-bold mb-2 text-uppercase" style="font-size: 11px;">Isi Dokumen:</h6>
                            <ul class="list-group list-group-flush bg-transparent">
                                <li class="list-group-item bg-transparent border-0 py-1 pl-0">
                                    <i class="far fa-file-pdf text-danger mr-2"></i> Surat Keputusan (SK) 00${i}
                                </li>
                                <li class="list-group-item bg-transparent border-0 py-1 pl-0">
                                    <i class="far fa-file-image text-primary mr-2"></i> Lampiran Dokumentasi
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>`;
            }

            $('#kontenAccordionBantex').html(html);
            $('#modalDetailBantex').modal('show');
        }

        // --- 3. REVIEW SUBMIT (Klik Tombol Submit) ---
        function reviewSubmit(id_transaksi, divisi, total_box, total_bantex) {
            $('#reviewDivisi').text(divisi);
            $('#btnEditData').attr('href', '?module=form_entri_barang_masuk&id=' + id_transaksi);
            $('#btnFinalSubmit').attr('href', 'modules/barang-masuk/proses_acc.php?id=' + id_transaksi + '&act=simpan');

            let html = '';
            let bantexPerBox = Math.ceil(total_bantex / total_box);
            let bantexCounter = 1;

            for (let b = 1; b <= total_box; b++) {
                let rfid = "RF-" + Math.floor(100000 + Math.random() * 900000);
                
                html += `
                <div class="card mb-3 border border-secondary">
                    <div class="card-header bg-white font-weight-bold d-flex justify-content-between">
                        <span><i class="fas fa-box-open text-secondary mr-2"></i> Box ${b}</span>
                        <span class="badge badge-secondary">${rfid}</span>
                    </div>
                    <div class="card-body bg-light p-2">
                        <div class="accordion" id="accReviewBox${b}">`;

                let limitBantex = bantexCounter + bantexPerBox;
                if(limitBantex > total_bantex + 1) limitBantex = total_bantex + 1;

                for (let x = bantexCounter; x < limitBantex; x++) {
                    if (x > total_bantex) break; 
                    let idHead = `revHead${b}_${x}`;
                    let idColl = `revColl${b}_${x}`;

                    html += `
                        <div class="card mb-1 border-0 shadow-none">
                            <div class="card-header p-0" id="${idHead}">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left collapsed text-dark" type="button" data-toggle="collapse" data-target="#${idColl}">
                                        <i class="fas fa-folder text-warning mr-2"></i> Bantex ${x}
                                    </button>
                                </h2>
                            </div>
                            <div id="${idColl}" class="collapse" data-parent="#accReviewBox${b}">
                                <div class="card-body bg-white pl-5 py-2">
                                    <ul class="list-unstyled mb-0 small">
                                        <li><i class="far fa-file-pdf text-danger mr-1"></i> Dokumen A</li>
                                        <li><i class="far fa-file-alt text-success mr-1"></i> Dokumen B</li>
                                    </ul>
                                </div>
                            </div>
                        </div>`;
                    bantexCounter++;
                }
                html += `</div></div></div>`;
            }
            $('#kontenReviewLengkap').html(html);
            $('#modalReview').modal('show');
        }
    </script>
<?php } ?>