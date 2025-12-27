<?php
// mencegah direct access file PHP
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('location: 404.html');
} else { 
    // Daftar Divisi sesuai permintaan
    $daftar_divisi = [
        "DSPN" => "Divisi Sekretariat Perusahaan",
        "DTPI" => "Divisi Satuan Pengawasan Intern",
        "DTAN" => "Divisi Tanaman",
        "DTPL" => "Divisi Teknik & Pengolahan",
        "DINF" => "Divisi Infrastruktur",
        "DITN" => "Divisi Investasi Tanaman",
        "DPSN" => "Divisi Pemasaran",
        "DRPL" => "Divisi Rantai Pasok & Logistik",
        "DPEN" => "Divisi Pengadaan",
        "DSKP" => "Divisi Strategi Perusahaan & Pengendalian Kinerja Anak Perusahaan",
        "DSMS" => "Divisi Sistem Manajemen & Sustainability",
        "DRPH" => "Divisi Riset, Pengembangan Bisnis & Hilirisasi",
        "DKSH" => "Divisi Keuangan Strategis dan Hubungan Investor",
        "DPBA" => "Divisi Perbendaharaan & Anggaran",
        "DAPN" => "Divisi Akuntansi & Perpajakan",
        "DMRS" => "Divisi Manajemen Risiko",
        "DPSB" => "Divisi Pengembangan SDM dan Budaya",
        "DSDM" => "Divisi Operasional SDM",
        "DHPU" => "Divisi HPS & Umum",
        "DTIS" => "Divisi Teknologi Informasi",
        "DHKT" => "Divisi Hubungan Kelembagaan dan TJSL",
        "DHKM" => "Divisi Hukum",
        "DPSR" => "Divisi PSR dan Plasma",
        "DPMO" => "Project Management Office"
    ];
?>

    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-4">
            <div class="page-header text-white">
                <h4 class="page-title text-white"><i class="fas fa-archive mr-2"></i> Repository Arsip</h4>
                <ul class="breadcrumbs">
                    <li class="nav-home"><a href="?module=dashboard"><i class="flaticon-home text-white"></i></a></li>
                    <li class="separator"><i class="flaticon-right-arrow"></i></li>
                    <li class="nav-item"><a href="?module=barang_masuk" class="text-white">Arsip</a></li>
                    <li class="separator"><i class="flaticon-right-arrow"></i></li>
                    <li class="nav-item"><a>Input Baru</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="page-inner mt--5">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Kelola dokumen dan bantex arsip dengan mudah</div>
            </div>
            
            <form action="modules/barang-masuk/proses_simpan.php" method="post" id="formArsip">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Divisi <span class="text-danger">*</span></label>
                                <select name="divisi" class="form-control select2" required>
                                    <option value="">-- Pilih Divisi --</option>
                                    <?php foreach($daftar_divisi as $kode => $nama): ?>
                                        <option value="<?= $kode ?>"><?= $kode ?> - <?= $nama ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Lokasi Arsip <span class="text-danger">*</span></label>
                                <select name="lokasi_arsip" class="form-control" required>
                                    <option value="HO" selected>Head Office (HO)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="font-weight-bold">Bantex & Dokumen</h4>
                        <div>
                            <span class="badge badge-primary">Total Bantex: <span id="count-bantex">0</span></span>
                            <span class="badge badge-warning">Total Box: <span id="count-box">0</span></span>
                        </div>
                    </div>

                    <div id="box-container">
                        <div class="alert alert-light text-center border-dashed" id="empty-state">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada bantex ditambahkan. Klik tombol di bawah untuk memulai.</p>
                        </div>
                    </div>

                    <button type="button" class="btn btn-primary btn-block mt-3" data-toggle="modal" data-target="#modalBantex">
                        <i class="fas fa-plus mr-2"></i> Tambah Bantex
                    </button>

                    <br><br>

                    <div class="row">
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-success btn-block btn-lg">
                                <i class="fas fa-paper-plane mr-2"></i> Submit Arsip
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button type="reset" class="btn btn-secondary btn-block btn-lg" onclick="resetForm()">
                                <i class="fas fa-times-circle mr-2"></i> Reset
                            </button>
                        </div>
                        <div class="col-md-4">
                            <a href="?module=barang_masuk" class="btn btn-info btn-block btn-lg">
                                <i class="fas fa-list mr-2"></i> Lihat Data Surat Masuk
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="alert alert-info mt-3" role="alert">
            <i class="fas fa-info-circle mr-2"></i> 
            <strong>Informasi:</strong> Setiap kotak dapat menampung hingga 6 bantex. Sistem akan secara otomatis membuat kotak baru ketika jumlah bantex mencapai 6.
        </div>
    </div>

    <div class="modal fade" id="modalBantex" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">Form Bantex Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Bantex <span class="text-danger">*</span></label>
                        <input type="text" id="modal_nama_bantex" class="form-control" placeholder="Contoh: Bantex Kontrak 2023">
                    </div>

                    <label>Dokumen dalam Bantex <span class="text-danger">*</span></label>
                    <div class="input-group mb-2">
                        <input type="text" id="modal_nama_dokumen" class="form-control" placeholder="Nama dokumen">
                        <input type="text" id="modal_periode_dokumen" class="form-control col-3" placeholder="Periode (V/2023)">
                        <div class="input-group-append">
                            <button class="btn btn-secondary" type="button" id="btn-add-doc-list">+ Tambah Dokumen</button>
                        </div>
                    </div>
                    
                    <ul class="list-group mb-3" id="temp-doc-list">
                        </ul>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success btn-block" id="btn-simpan-bantex">
                        <i class="fas fa-save mr-2"></i> Simpan Bantex
                    </button>
                    <button type="button" class="btn btn-secondary btn-block mt-0" data-dismiss="modal">
                        <i class="fas fa-times mr-2"></i> Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Variabel Global menyimpan data bantex
        let bantexList = [];
        const MAX_BANTEX_PER_BOX = 6;
        let tempDocs = []; // Dokumen sementara di dalam modal

        $(document).ready(function() {
            // 1. Logika Menambah Dokumen di dalam Modal
            $('#btn-add-doc-list').click(function(){
                let docName = $('#modal_nama_dokumen').val();
                let docPeriod = $('#modal_periode_dokumen').val();

                if(docName === "") {
                    alert("Nama dokumen tidak boleh kosong!");
                    return;
                }

                let docObj = { nama: docName, periode: docPeriod };
                tempDocs.push(docObj);

                // Render ke list sementara
                renderTempDocs();
                
                // Reset input dokumen
                $('#modal_nama_dokumen').val('');
                $('#modal_periode_dokumen').val('');
                $('#modal_nama_dokumen').focus();
            });

            // 2. Logika Simpan Bantex dari Modal ke Halaman Utama
            $('#btn-simpan-bantex').click(function(){
                let namaBantex = $('#modal_nama_bantex').val();
                
                if(namaBantex === "") {
                    alert("Nama Bantex harus diisi!");
                    return;
                }
                if(tempDocs.length === 0) {
                    if(!confirm("Anda belum menambahkan dokumen ke dalam bantex ini. Lanjutkan?")) return;
                }

                // Masukkan ke array global
                bantexList.push({
                    nama: namaBantex,
                    dokumen: tempDocs
                });

                // Reset Modal & Variable Sementara
                $('#modal_nama_bantex').val('');
                tempDocs = [];
                $('#temp-doc-list').empty();
                $('#modalBantex').modal('hide');

                // Render Ulang Tampilan Box
                renderBoxes();
            });
        });

        // Fungsi Render List Dokumen di Modal
        function renderTempDocs() {
            let html = '';
            tempDocs.forEach((doc, index) => {
                html += `<li class="list-group-item d-flex justify-content-between align-items-center p-2">
                            ${doc.nama} <span class="badge badge-info">${doc.periode}</span>
                            <button type="button" class="btn btn-sm btn-danger btn-round" onclick="removeTempDoc(${index})">x</button>
                         </li>`;
            });
            $('#temp-doc-list').html(html);
        }

        function removeTempDoc(index) {
            tempDocs.splice(index, 1);
            renderTempDocs();
        }

        // Fungsi Utama: Render Box dan Bantex
        function renderBoxes() {
            let container = $('#box-container');
            container.empty();

            if (bantexList.length === 0) {
                $('#empty-state').show();
                $('#count-bantex').text(0);
                $('#count-box').text(0);
                return;
            }

            $('#empty-state').hide();
            
            let currentBoxIndex = 1;
            let html = '';

            // Loop setiap 6 item untuk membuat box baru
            for (let i = 0; i < bantexList.length; i += MAX_BANTEX_PER_BOX) {
                // Slice array untuk mendapatkan 6 item (atau sisa)
                let chunk = bantexList.slice(i, i + MAX_BANTEX_PER_BOX);
                
                html += `
                <div class="card bg-light border mb-3">
                    <div class="card-body p-3">
                        <h5 class="text-primary font-weight-bold mb-3"><i class="fas fa-box mr-2"></i> Box ${currentBoxIndex}</h5>
                        <div class="row">`;

                // Loop bantex di dalam box ini
                chunk.forEach((bantex, idx) => {
                    let realIdx = i + idx; // Index asli di array global
                    let docCount = bantex.dokumen.length;
                    
                    html += `
                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm border-left-primary h-100">
                                <div class="card-body p-2">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="font-weight-bold mb-1 text-truncate" title="${bantex.nama}">${bantex.nama}</h6>
                                        <button type="button" class="btn btn-xs btn-link text-danger" onclick="deleteBantex(${realIdx})"><i class="fas fa-times"></i></button>
                                    </div>
                                    <small class="text-muted"><i class="fas fa-file-alt"></i> ${docCount} Dokumen</small>
                                    
                                    <input type="hidden" name="box[${currentBoxIndex}][bantex][${idx}][nama]" value="${bantex.nama}">
                                    ${generateHiddenDocs(bantex.dokumen, currentBoxIndex, idx)}
                                </div>
                            </div>
                        </div>`;
                });

                html += `   </div>
                        </div>
                    </div>`;
                
                currentBoxIndex++;
            }

            container.html(html);
            $('#count-bantex').text(bantexList.length);
            $('#count-box').text(currentBoxIndex - 1);
        }

        // Helper untuk generate input hidden dokumen
        function generateHiddenDocs(docs, boxId, bantexId) {
            let inputs = '';
            docs.forEach((doc, docId) => {
                inputs += `<input type="hidden" name="box[${boxId}][bantex][${bantexId}][dokumen][${docId}][nama]" value="${doc.nama}">`;
                inputs += `<input type="hidden" name="box[${boxId}][bantex][${bantexId}][dokumen][${docId}][periode]" value="${doc.periode}">`;
            });
            return inputs;
        }

        // Hapus Bantex dari list
        function deleteBantex(index) {
            if(confirm("Hapus bantex ini?")) {
                bantexList.splice(index, 1);
                renderBoxes();
            }
        }

        function resetForm() {
            if(confirm("Reset semua data input?")) {
                bantexList = [];
                renderBoxes();
                return true;
            }
            return false;
        }
    </script>
<?php } ?>