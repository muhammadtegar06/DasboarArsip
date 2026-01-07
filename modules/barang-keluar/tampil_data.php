<?php
// Tampil Data Barang Masuk (Mode: Data Dummy & Input Dokumen)
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('location: 404.html');
}
else {
    // --- 1. MEMBUAT DATA DUMMY ---
    // Kita buat array manual untuk menggantikan Database sementara
    $data_dummy = [
        [
            'id_transaksi' => 2025001,
            'divisi'       => 'DTIS - Divisi Teknologi Informasi',
            'tanggal'      => '2025-12-30',
            'total_box'    => 5,
            'jumlah'       => 30, // Total Bantex
            'status'       => 'Diterima' // Status Simulasi
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
            'status'       => 'Ditolak' // Contoh status Ditolak
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
                            
                            // --- 2. LOOPING DATA DUMMY ---
                            // Menggunakan foreach pada array $data_dummy, bukan mysqli_fetch_assoc
                            foreach ($data_dummy as $data) { 
                                $id_transaksi = $data['id_transaksi'];
                                $divisi       = $data['divisi'];
                                $tanggal      = date('d M Y', strtotime($data['tanggal']));
                                $total_box    = $data['total_box'];
                                $total_bantex = $data['jumlah'];
                                $status       = $data['status']; // Ambil status dari dummy
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
                                            <button type="button" 
                                                onclick="bukaModalInputDokumen('<?php echo $id_transaksi; ?>', '<?php echo $divisi; ?>', <?php echo $total_bantex; ?>)" 
                                                class="btn btn-primary btn-round btn-sm shadow font-weight-bold">
                                                <i class="fas fa-file-import mr-2"></i> Input Dokumen
                                            </button>
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
                <div class="mt-3 small text-muted font-italic">
                    * Data di atas adalah data dummy statis untuk keperluan demonstrasi UI.
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalInputDokumen" tabindex="-1" role="dialog" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white font-weight-bold">
                        <i class="fas fa-folder-open mr-2"></i> Input Dokumen Arsip
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body bg-light">
                    
                    <div class="bg-white p-3 rounded shadow-sm border mb-3">
                        <div class="row align-items-center">
                            <div class="col-md-8 border-right">
                                <small class="text-muted text-uppercase font-weight-bold" style="font-size:10px;">Divisi Pengaju</small>
                                <h5 class="text-dark font-weight-bold mb-0" id="inputModalDivisi">-</h5>
                            </div>
                            <div class="col-md-4 text-right">
                                <small class="text-muted text-uppercase font-weight-bold" style="font-size:10px;">ID Transaksi</small>
                                <h5 class="text-primary font-weight-bold mb-0" id="inputModalID">-</h5>
                            </div>
                        </div>
                    </div>

                    <form action="#" method="POST" enctype="multipart/form-data" onsubmit="alert('Simulasi: Data dokumen berhasil disimpan!'); return false;">
                        <input type="hidden" name="id_transaksi" id="formIdTransaksi">
                        
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <h6 class="font-weight-bold border-bottom pb-2 mb-3 text-secondary">
                                    <i class="fas fa-edit mr-2"></i> Detail Dokumen
                                </h6>
                                
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label font-weight-bold">Lokasi Bantex <span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <select class="form-control select2-modal" name="nomor_bantex" id="selectBantex" required style="width:100%">
                                            <option value="">-- Pilih Nomor Bantex --</option>
                                            </select>
                                        <small class="text-muted">Pilih bantex fisik tempat dokumen disimpan.</small>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label font-weight-bold">Judul Dokumen <span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="nama_dokumen" placeholder="Contoh: Perjanjian Kerjasama PT. Maju Mundur" required>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="font-weight-bold">Nomor Dokumen</label>
                                        <input type="text" class="form-control" name="no_dokumen" placeholder="Contoh: 001/SPK/XII/2025">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="font-weight-bold">Tahun Arsip</label>
                                        <select class="form-control" name="tahun_dokumen">
                                            <?php 
                                            $thn_skrg = date('Y');
                                            for($t = $thn_skrg; $t >= $thn_skrg - 10; $t--){
                                                echo "<option value='$t'>$t</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold">Scan File <span class="text-muted small">(PDF/JPG, Max 2MB)</span></label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="customFile" name="file_dokumen">
                                        <label class="custom-file-label" for="customFile">Pilih file...</label>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="text-right mt-4">
                            <button type="button" class="btn btn-secondary btn-round mr-2" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary btn-round px-4 font-weight-bold shadow">
                                <i class="fas fa-save mr-2"></i> Simpan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Custom file input label change
            $('.custom-file-input').on('change', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName);
            });
        });

        // FUNGSI MEMBUKA MODAL INPUT DOKUMEN
        function bukaModalInputDokumen(id, divisi, totalBantex) {
            // 1. Isi Data Header
            $('#inputModalDivisi').text(divisi);
            $('#inputModalID').text("#TRANS-" + id);
            $('#formIdTransaksi').val(id);

            // 2. Generate Dropdown Bantex (Sesuai Jumlah di Data Dummy)
            let options = '<option value="">-- Pilih Bantex (1 - '+ totalBantex +') --</option>';
            
            // Logika: Estimasi 1 Box = 6 Bantex (hanya visualisasi)
            // Kita loop sesuai total_bantex yang dilempar dari data dummy
            for (let i = 1; i <= totalBantex; i++) {
                // Tentukan dia ada di box ke berapa
                let boxKe = Math.ceil(i / 6); 
                options += `<option value="${i}">Bantex Ke-${i} (Box ${boxKe})</option>`;
            }
            
            $('#selectBantex').html(options);

            // 3. Buka Modal
            $('#modalInputDokumen').modal('show');
        }
    </script>

<?php } ?>