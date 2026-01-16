<?php
// Mencegah direct access
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('location: 404.html');
}

// 1. DATA DIVISI (Simulasi)
$divisi_list = [
    "DSPN" => "Divisi Sekretariat Perusahaan",
    "DTPI" => "Divisi Satuan Pengawasan Intern",
    "DTAN" => "Divisi Tanaman",
    "DTPL" => "Divisi Teknik & Pengolahan",
    "DINF" => "Divisi Infrastruktur",
    "DITN" => "Divisi Investasi Tanaman",
    "DPSN" => "Divisi Pemasaran",
    "DRPL" => "Divisi Rantai Pasok & Logistik",
    "DPEN" => "Divisi Pengadaan",
    "DHKM" => "Divisi Hukum",
    "DSDM" => "Divisi Operasional SDM",
    "DTIS" => "Divisi Teknologi Informasi",
    "DKEU" => "Divisi Keuangan"
];

// 2. HELPER FUNCTIONS
function getMonthOptions() {
    $months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    $opts = '<option value="">- Bulan -</option>';
    foreach ($months as $m) $opts .= "<option value='$m'>$m</option>";
    return $opts;
}

function getYearOptions() {
    $curr = date('Y');
    $opts = '<option value="">- Tahun -</option>';
    for ($i = $curr; $i >= $curr - 5; $i--) $opts .= "<option value='$i'>$i</option>";
    return $opts;
}

// 3. MENANGKAP DATA DARI URL
$id_transaksi = isset($_GET['id']) ? $_GET['id'] : '-';
$nama_divisi_url = isset($_GET['divisi']) ? urldecode($_GET['divisi']) : '';

$kode_divisi_selected = "";
foreach ($divisi_list as $kd => $nm) {
    if (strpos($nama_divisi_url, $kd) !== false) {
        $kode_divisi_selected = $kd;
        break;
    }
}
?>

<style>
    /* General Layout */
    .page-inner { padding-top: 25px; background: #f9fbfd; }
    .card { border: none; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); border-radius: 12px; }
    .card-header { background: #fff; border-bottom: 1px solid #f1f1f1; border-radius: 12px 12px 0 0 !important; padding: 20px 25px; }

    /* Typography */
    .repo-title { font-size: 24px; font-weight: 800; color: #2c3e50; margin-bottom: 5px; }
    .repo-subtitle { color: #7f8c8d; font-size: 14px; }

    /* Stats Badges */
    .stat-badge { font-size: 13px; font-weight: 600; padding: 8px 15px; border-radius: 30px; margin-left: 10px; }
    .stat-badge.blue { background: #e3f2fd; color: #1565c0; }
    .stat-badge.green { background: #e8f5e9; color: #2e7d32; }

    /* Box Container */
    .box-wrapper { background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 8px; padding: 20px; margin-bottom: 20px; position: relative; }
    .box-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
    .box-title { font-weight: 700; color: #4e73df; font-size: 16px; text-transform: uppercase; letter-spacing: 0.5px; }
    .box-badge { background: #fff; border: 1px solid #4e73df; color: #4e73df; padding: 2px 10px; border-radius: 4px; font-size: 11px; font-weight: 700; }

    /* Bantex Card */
    .bantex-item { background: #fff; border-left: 4px solid #3498db; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02); border-radius: 4px; margin-bottom: 10px; transition: transform 0.2s; position: relative; }
    .bantex-item:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05); }
    .bantex-content { padding: 15px; }
    .bantex-name { font-weight: 700; color: #2c3e50; font-size: 14px; margin-bottom: 8px; }
    .doc-list-item { font-size: 11px; color: #666; padding: 2px 0; display: flex; align-items: center; }
    .doc-list-item i { margin-right: 8px; color: #bdc3c7; }
    .btn-delete-bantex { position: absolute; top: 10px; right: 10px; color: #e74c3c; cursor: pointer; opacity: 0.5; transition: 0.2s; z-index: 10; }
    .btn-delete-bantex:hover { opacity: 1; transform: scale(1.1); }

    /* Custom Upload Area (Utama) */
    .upload-area {
        border: 2px dashed #d1d3e2; border-radius: 8px; padding: 25px; text-align: center; background: #fff; cursor: pointer; transition: 0.3s; position: relative;
    }
    .upload-area:hover { background: #f8f9fc; border-color: #4e73df; }
    .upload-icon { font-size: 2rem; color: #4e73df; margin-bottom: 10px; }
    .upload-text { font-weight: 600; color: #5a5c69; margin-bottom: 0; }
    .upload-hint { font-size: 0.8rem; color: #858796; }
    .file-selected { border-color: #1cc88a; background: #f0fdf4; }
    .file-selected .upload-icon { color: #1cc88a; }
    
    /* File Actions (Utama) */
    .main-file-actions { display: none; margin-top: 15px; justify-content: center; gap: 10px; }
    .file-selected .main-file-actions { display: flex; }

    /* Inline Form Area */
    .inline-form-area { background: #fff; border: 2px dashed #d1d3e2; border-radius: 10px; padding: 25px; margin-bottom: 20px; display: none; }
    .form-section-title { font-size: 15px; font-weight: 700; color: #5a5c69; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px; }

    /* Small Upload Button Group (In Row) */
    .file-btn-group { display: flex; align-items: center; width: 100%; }
    .btn-upload-trigger { font-size: 12px; font-weight: 600; color: #1976d2; background: #e3f2fd; border: 1px solid #bbdefb; width: 100%; text-align: center; padding: 6px; border-radius: 4px; transition: 0.2s; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .btn-upload-trigger:hover { background: #bbdefb; }
    
    .file-actions-row { display: none; width: 100%; align-items: center; gap: 5px; }
    .btn-view-file { font-size: 11px; padding: 5px 8px; flex-grow: 1; text-align: left; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; background: #e8f5e9; border: 1px solid #c8e6c9; color: #2e7d32; border-radius: 4px; }
    .btn-delete-file { padding: 5px 8px; font-size: 11px; background: #fee2e2; border: 1px solid #fecaca; color: #b91c1c; border-radius: 4px; }

    /* Buttons */
    .btn-xl { padding: 12px 20px; font-size: 16px; font-weight: 700; border-radius: 8px; }
    .btn-add-bantex { border: 2px dashed #4e73df; color: #4e73df; background: #f4f7fe; font-weight: 700; width: 100%; padding: 15px; border-radius: 10px; transition: 0.3s; cursor: pointer; }
    .btn-add-bantex:hover { background: #4e73df; color: #fff; border-color: #4e73df; }

    /* Modal Styles */
    .modal-header-custom { border-bottom: 1px solid #f0f0f0; padding: 20px 25px; }
    .modal-title-custom { font-weight: 700; color: #2c3e50; font-size: 18px; }
    .info-label { font-size: 10px; font-weight: 700; color: #8898aa; text-transform: uppercase; margin-bottom: 4px; }
    .info-value { font-size: 15px; font-weight: 600; color: #333; margin-bottom: 15px; }
    .modal-bantex-card { background-color: #f4f8fb; border: 1px solid #dbeafe; border-left: 4px solid #4e73df; border-radius: 6px; padding: 12px 15px; margin-bottom: 10px; }
    .modal-bantex-title { color: #2e59d9; font-weight: 700; font-size: 14px; margin-bottom: 5px; }
    .summary-box { background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 15px; margin-top: 20px; text-align: center; }
    .summary-label { font-size: 11px; font-weight: 700; color: #15803d; text-transform: uppercase; }
    .summary-number { font-size: 28px; font-weight: 800; color: #16a34a; line-height: 1.2; }
    .summary-note { font-size: 11px; color: #16a34a; margin-top: 5px; }
</style>

<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-4">
        <div class="d-flex align-items-left align-items-md-top flex-column flex-md-row">
            <div>
                <h2 class="text-white pb-2 fw-bold">Repository Arsip</h2>
                <h5 class="text-white op-7 mb-2">Formulir Digitalisasi Arsip Divisi</h5>
            </div>
        </div>
    </div>
</div>

<div class="page-inner mt--5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <div class="repo-title">Entri Data Baru</div>
                        <div class="repo-subtitle">ID Transaksi: <b>#TRANS-<?= $id_transaksi ?></b></div>
                    </div>
                    <div class="d-none d-md-block">
                        <i class="fas fa-file-invoice fa-3x text-light-gray opacity-25" style="color: #e3e6f0;"></i>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Divisi Pengaju <span class="text-danger">*</span></label>
                                <select id="divisi" class="form-control select2" style="width:100%">
                                    <option value="">-- Pilih Divisi --</option>
                                    <?php foreach ($divisi_list as $kode => $nama): ?>
                                        <?php $selected = ($kode == $kode_divisi_selected) ? 'selected' : ''; ?>
                                        <option value="<?= $kode ?>" <?= $selected ?>><?= $kode ?> - <?= $nama ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Lokasi Arsip <span class="text-danger">*</span></label>
                                <select id="lokasi_arsip" class="form-control">
                                    <option value="HO" selected>Head Office (HO)</option>
                                    <option value="Gudang">Gudang Arsip Pusat</option>
                                    <option value="Pabrik">Kantor Pabrik</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="font-weight-bold">Upload Surat Pengantar/Pengajuan <span class="text-danger">*</span></label>
                                
                                <input type="file" id="fileSurat" accept=".pdf,.doc,.docx,.jpg,.png" style="display:none;" onchange="handleMainFileSelect(this)">
                                
                                <div class="upload-area" id="uploadArea">
                                    <div onclick="document.getElementById('fileSurat').click()">
                                        <i class="fas fa-cloud-upload-alt upload-icon" id="uploadIcon"></i>
                                        <p class="upload-text" id="uploadText">Klik disini untuk upload surat pengantar</p>
                                        <small class="upload-hint" id="uploadHint">Format: PDF, DOC, JPG (Maks. 30MB)</small>
                                    </div>
                                    
                                    <div class="main-file-actions">
                                        <button type="button" class="btn btn-sm btn-info btn-round" onclick="viewMainFile()">
                                            <i class="fas fa-eye mr-1"></i> Lihat File
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger btn-round" onclick="deleteMainFile()">
                                            <i class="fas fa-trash mr-1"></i> Hapus
                                        </button>
                                        <button type="button" class="btn btn-sm btn-secondary btn-round" onclick="document.getElementById('fileSurat').click()">
                                            <i class="fas fa-sync-alt mr-1"></i> Ganti
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="font-weight-bold text-dark mb-0">
                            <i class="fas fa-layer-group text-primary mr-2"></i>Bantex & Dokumen
                        </h4>
                        <div>
                            <span class="stat-badge blue"><i class="fas fa-folder mr-1"></i> <span id="countBantex">0</span> Bantex</span>
                            <span class="stat-badge green"><i class="fas fa-box mr-1"></i> <span id="countBox">0</span> Box</span>
                        </div>
                    </div>

                    <div id="mainContainer">
                        <div id="emptyState" class="text-center py-5 border rounded bg-light mb-3">
                            <i class="fas fa-box-open fa-4x text-muted mb-3 opacity-50"></i>
                            <h5 class="text-muted font-weight-bold">Belum ada bantex ditambahkan</h5>
                            <p class="text-muted small">Klik tombol di bawah untuk mulai mengisi arsip</p>
                        </div>
                    </div>

                    <button type="button" id="btnShowAdd" class="btn-add-bantex mb-4" onclick="toggleForm(true)">
                        <i class="fas fa-plus-circle mr-2"></i> Tambah Bantex Baru
                    </button>

                    <div id="inlineForm" class="inline-form-area shadow-sm">
                        <div class="d-flex justify-content-between align-items-center form-section-title">
                            <span><i class="fas fa-edit mr-2"></i>Form Bantex Baru</span>
                            <button type="button" class="btn btn-sm btn-icon btn-light" onclick="toggleForm(false)"><i class="fas fa-times"></i></button>
                        </div>

                        <div class="form-group px-0">
                            <label class="font-weight-bold small text-uppercase">Nama / Label Bantex <span class="text-danger">*</span></label>
                            <input type="text" id="inputNamaBantex" class="form-control" placeholder="Contoh: Arsip Kontrak 2024 (Jan-Jun)">
                        </div>

                        <div class="bg-light p-3 rounded border mb-3">
                            <label class="font-weight-bold small text-uppercase mb-3 d-block">
                                Daftar Dokumen di dalam Bantex <span class="text-danger">*</span>
                            </label>
                            
                            <div class="row no-gutters mb-2 text-muted small font-weight-bold">
                                <div class="col-md-3 pr-2">Nama Dokumen</div>
                                <div class="col-md-3 pr-2">Nomor Surat</div>
                                <div class="col-md-3 pr-2">Periode Surat</div>
                                <div class="col-md-2 pr-2">Upload File</div>
                                <div class="col-md-1"></div>
                            </div>

                            <div id="docRows"></div>
                            
                            <button type="button" class="btn btn-sm btn-secondary mt-3" onclick="addDocRow()">
                                <i class="fas fa-plus mr-1"></i> Tambah Baris Dokumen
                            </button>
                        </div>

                        <div class="text-right">
                            <button type="button" class="btn btn-secondary mr-2" onclick="toggleForm(false)">Batal</button>
                            <button type="button" class="btn btn-success px-4 font-weight-bold" onclick="saveBantex()">
                                <i class="fas fa-check mr-2"></i> Simpan Bantex
                            </button>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-md-4 mb-2">
                            <button type="button" onclick="validateAndSubmit()" class="btn btn-success btn-xl btn-block shadow">
                                <i class="fas fa-paper-plane mr-2"></i> Submit Arsip
                            </button>
                        </div>
                        <div class="col-md-4 mb-2">
                            <button type="button" onclick="resetForm()" class="btn btn-secondary btn-xl btn-block">
                                <i class="fas fa-sync-alt mr-2"></i> Reset
                            </button>
                        </div>
                        <div class="col-md-4 mb-2">
                            <a href="?module=barang_masuk" class="btn btn-info btn-xl btn-block" style="background-color: #36b9cc; border-color: #36b9cc;">
                                Lihat Data
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalKonfirmasi" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title-custom">Konfirmasi Data Surat Masuk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-4">
                <div class="bg-white rounded mb-3">
                    <h6 class="font-weight-bold text-dark mb-3">Informasi Pengajuan</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-label">DIVISI</div>
                            <div class="info-value" id="viewDivisi">-</div>
                            <div class="info-label">SURAT PENGANTAR</div>
                            <div class="info-value text-primary" id="viewFileSurat">-</div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-label">LOKASI ARSIP</div>
                            <div class="info-value" id="viewLokasi">-</div>
                            <div class="info-label">TOTAL BANTEX</div>
                            <div class="info-value"><span id="viewTotalBantex">0</span> Bantex</div>
                        </div>
                    </div>
                </div>

                <h6 class="font-weight-bold text-dark mb-3">Daftar Dokumen</h6>
                <div id="modalDocList" style="max-height: 250px; overflow-y: auto;"></div>

                <div class="summary-box">
                    <div class="row">
                        <div class="col-6 border-right border-success">
                            <div class="summary-label">TOTAL BANTEX</div>
                            <div class="summary-number" id="sumBantex">0</div>
                        </div>
                        <div class="col-6">
                            <div class="summary-label">TOTAL BOX</div>
                            <div class="summary-number" id="sumBox">0</div>
                        </div>
                    </div>
                    <div class="summary-note">(6 Bantex = 1 Box)</div>
                </div>
            </div>

            <div class="modal-footer border-top-0 pt-0 pb-4 px-4 justify-content-between">
                <button type="button" class="btn btn-secondary btn-round font-weight-bold" style="background-color: #e2e8f0; color: #475569; border:none;" data-dismiss="modal">Kembali Edit</button>
                <button type="button" onclick="finalSubmit()" class="btn btn-success btn-round px-4 font-weight-bold shadow" style="background-color: #10b981; border:none;">Konfirmasi & Submit</button>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    // State Management
    let bantexList = [];
    const MAX_PER_BOX = 6;
    let selectedMainFile = null; 

    $(document).ready(function () {
        $('.select2').select2({ theme: "bootstrap" });
        addDocRow(); 
    });

    // --- 1. HANDLE UI & FILE UPLOAD UTAMA ---
    function handleMainFileSelect(input) {
        if (input.files && input.files[0]) {
            let file = input.files[0];
            let fileName = file.name;
            let fileSize = file.size; 
            let maxSize = 30 * 1024 * 1024; // 30 MB

            if (fileSize > maxSize) {
                swal("Ukuran File Terlalu Besar", "Maksimal ukuran file adalah 30MB.", "error");
                input.value = ""; 
                deleteMainFile();
                return;
            }

            selectedMainFile = file;
            let fileSizeMB = (fileSize / (1024 * 1024)).toFixed(2);
            $('#uploadArea').addClass('file-selected');
            $('#uploadIcon').removeClass('fa-cloud-upload-alt').addClass('fa-check-circle');
            $('#uploadText').text(fileName);
            $('#uploadHint').text('Ukuran: ' + fileSizeMB + ' MB');
        }
    }

    function viewMainFile() {
        if (selectedMainFile) {
            let fileURL = URL.createObjectURL(selectedMainFile);
            window.open(fileURL, '_blank');
        } else {
            swal("Info", "Tidak ada file untuk dilihat.", "info");
        }
    }

    function deleteMainFile() {
        selectedMainFile = null;
        $('#fileSurat').val(''); // Reset input file
        $('#uploadArea').removeClass('file-selected');
        $('#uploadIcon').addClass('fa-cloud-upload-alt').removeClass('fa-check-circle');
        $('#uploadText').text('Klik disini untuk upload surat pengantar');
        $('#uploadHint').text('Format: PDF, DOC, JPG (Maks. 30MB)');
    }

    // --- 2. HANDLE DOC ROW & FILE PER DOCUMENT ---
    function createDocRowHtml() {
        let mOpts = `<?= getMonthOptions() ?>`;
        let yOpts = `<?= getYearOptions() ?>`;

        return `
        <div class="row no-gutters mb-2 align-items-center doc-row">
            <div class="col-md-3 pr-2">
                <input type="text" class="form-control form-control-sm doc-name" placeholder="Nama Dokumen">
            </div>
            <div class="col-md-3 pr-2">
                <input type="text" class="form-control form-control-sm doc-number" placeholder="Nomor Surat">
            </div>
            <div class="col-md-3 pr-2">
                <div class="d-flex">
                    <select class="form-control form-control-sm doc-month mr-1" style="width:60%">${mOpts}</select>
                    <select class="form-control form-control-sm doc-year" style="width:40%">${yOpts}</select>
                </div>
            </div>
            <div class="col-md-2 pr-2">
                <div class="file-btn-group">
                    <input type="file" class="doc-file d-none" onchange="handleDocFile(this)">
                    
                    <button type="button" class="btn-upload-trigger" onclick="$(this).prev().click()">
                        <i class="fas fa-cloud-upload-alt"></i> Upload
                    </button>

                    <div class="file-actions-row">
                        <button type="button" class="btn-view-file" onclick="viewDocFile(this)" title="Lihat">File.pdf</button>
                        <button type="button" class="btn-delete-file" onclick="deleteDocFile(this)" title="Hapus"><i class="fas fa-times"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-md-1 text-center">
                <button type="button" class="btn btn-sm btn-link text-danger" onclick="$(this).closest('.doc-row').remove()"><i class="fas fa-times"></i></button>
            </div>
        </div>`;
    }

    function addDocRow() {
        $('#docRows').append(createDocRowHtml());
    }

    // Handle Upload File per Baris
    function handleDocFile(input) {
        if (input.files && input.files[0]) {
            let file = input.files[0];
            let maxSize = 30 * 1024 * 1024; // 30MB

            if (file.size > maxSize) {
                swal("Error", "Ukuran file maksimal 30MB", "error");
                input.value = "";
                return;
            }

            let container = $(input).parent();
            container.find('.btn-upload-trigger').hide();
            
            let actionRow = container.find('.file-actions-row');
            actionRow.css('display', 'flex');
            actionRow.find('.btn-view-file').text(file.name);
            
            // Simpan referensi file di elemen button view agar bisa dibuka
            actionRow.find('.btn-view-file').data('file-blob', file);
        }
    }

    function viewDocFile(btn) {
        let file = $(btn).data('file-blob');
        if (file) {
            let url = URL.createObjectURL(file);
            window.open(url, '_blank');
        }
    }

    function deleteDocFile(btn) {
        let container = $(btn).closest('.file-btn-group');
        container.find('input[type=file]').val(""); // Clear input
        container.find('.file-actions-row').hide();
        container.find('.btn-upload-trigger').show(); // Show upload btn again
    }

    function toggleForm(show) {
        if (show) {
            $('#btnShowAdd').hide();
            $('#inlineForm').slideDown();
            $('#inputNamaBantex').val('');
            $('#docRows').empty();
            addDocRow(); 
        } else {
            $('#inlineForm').slideUp();
            $('#btnShowAdd').fadeIn();
        }
    }

    // --- 3. LOGIC SIMPAN BANTEX ---
    function saveBantex() {
        let namaBantex = $('#inputNamaBantex').val();
        if (!namaBantex) { swal("Error", "Nama Bantex harus diisi!", "error"); return; }

        let docs = [];
        let isValid = true;

        $('.doc-row').each(function () {
            let name = $(this).find('.doc-name').val();
            let number = $(this).find('.doc-number').val();
            let month = $(this).find('.doc-month').val();
            let year = $(this).find('.doc-year').val();
            let fileInput = $(this).find('.doc-file')[0];
            let fileName = (fileInput.files.length > 0) ? fileInput.files[0].name : "Tidak ada file";

            // Validasi: Nama & Nomor wajib
            if (name && number) {
                docs.push({ 
                    name: name, 
                    number: number,
                    period: (month && year) ? `${month} ${year}` : '-',
                    file: fileName
                });
            }
        });

        if (docs.length === 0) { swal("Error", "Minimal isi 1 dokumen lengkap (Nama & No Surat)!", "error"); return; }

        bantexList.push({ nama_bantex: namaBantex, dokumen: docs });
        renderList();
        toggleForm(false);
    }

    function deleteBantex(index) {
        swal({
            title: "Hapus Bantex?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                bantexList.splice(index, 1);
                renderList();
            }
        });
    }

    function renderList() {
        let container = $('#mainContainer');
        container.empty();

        if (bantexList.length === 0) {
            container.html(`
                <div id="emptyState" class="text-center py-5 border rounded bg-light mb-3">
                    <i class="fas fa-box-open fa-4x text-muted mb-3 opacity-50"></i>
                    <h5 class="text-muted font-weight-bold">Belum ada bantex ditambahkan</h5>
                    <p class="text-muted small">Klik tombol di bawah untuk mulai mengisi arsip</p>
                </div>
            `);
            $('#countBantex').text(0);
            $('#countBox').text(0);
            return;
        }

        let totalBantex = bantexList.length;
        let totalBox = Math.ceil(totalBantex / MAX_PER_BOX);
        $('#countBantex').text(totalBantex);
        $('#countBox').text(totalBox);

        let boxCounter = 1;
        for (let i = 0; i < totalBantex; i += MAX_PER_BOX) {
            let chunk = bantexList.slice(i, i + MAX_PER_BOX);
            let bantexCards = '';
            chunk.forEach((b, idx) => {
                let globalIdx = i + idx;
                
                let docItems = b.dokumen.map(d =>
                    `<div class="doc-list-item">
                        <i class="far fa-file-alt text-primary"></i> 
                        <span class="text-dark font-weight-bold">${d.name}</span>
                        <span class="text-muted ml-1">[No: ${d.number}]</span>
                        <span class="text-muted ml-1 small">(${d.period})</span>
                        ${d.file !== "Tidak ada file" ? '<i class="fas fa-paperclip ml-2 text-success" title="Ada File"></i>' : ''}
                    </div>`
                ).join('');

                bantexCards += `
                <div class="col-md-6">
                    <div class="bantex-item">
                        <div class="bantex-content">
                            <div class="btn-delete-bantex" onclick="deleteBantex(${globalIdx})"><i class="fas fa-times-circle"></i></div>
                            <div class="bantex-name">${b.nama_bantex}</div>
                            ${docItems}
                        </div>
                    </div>
                </div>`;
            });

            let boxHtml = `
            <div class="box-wrapper">
                <div class="box-header">
                    <div class="box-title"><i class="fas fa-box-open mr-2"></i> BOX ${boxCounter}</div>
                    ${chunk.length === 6 ? '<span class="badge badge-danger">BOX PENUH</span>' : '<span class="box-badge">' + chunk.length + '/6 BANTEX</span>'}
                </div>
                <div class="row">${bantexCards}</div>
            </div>`;

            container.append(boxHtml);
            boxCounter++;
        }
    }

    function resetForm() {
        swal({
            title: "Reset Formulir?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willReset) => {
            if (willReset) {
                bantexList = [];
                deleteMainFile();
                $('#divisi').val('').trigger('change');
                renderList();
            }
        });
    }

    // --- 4. SUBMIT & CONFIRMATION ---
    function validateAndSubmit() {
        let div = $('#divisi').val();
        let lok = $('#lokasi_arsip').val();

        if (!div) { swal("Info", "Mohon pilih Divisi terlebih dahulu.", "warning"); return; }
        if (!selectedMainFile) { swal("Info", "Mohon upload Surat Pengantar terlebih dahulu.", "warning"); return; }
        if (bantexList.length === 0) { swal("Info", "Data masih kosong. Tambahkan minimal 1 Bantex.", "warning"); return; }

        $('#viewDivisi').text(div);
        $('#viewLokasi').text(lok);
        $('#viewFileSurat').text(selectedMainFile.name);
        $('#viewTotalBantex').text(bantexList.length);
        $('#sumBantex').text(bantexList.length);
        $('#sumBox').text(Math.ceil(bantexList.length / MAX_PER_BOX));

        let htmlList = '';
        bantexList.forEach((b, i) => {
            let docs = b.dokumen.map(d => `<li>${d.name} <span class="text-muted small">(${d.number})</span></li>`).join('');
            htmlList += `
            <div class="modal-bantex-card">
                <div class="modal-bantex-title">Bantex ${i + 1}: ${b.nama_bantex}</div>
                <ul class="pl-3 mb-0 small text-dark" style="list-style-type: disc;">${docs}</ul>
            </div>`;
        });
        $('#modalDocList').html(htmlList);

        $('#modalKonfirmasi').modal('show');
    }

    function finalSubmit() {
        // Simulasi Simpan
        let dataToSave = {
            divisi: $('#divisi option:selected').text(),
            lokasi: $('#lokasi_arsip').val(),
            tanggal: new Date().toISOString().slice(0, 10),
            total_box: Math.ceil(bantexList.length / MAX_PER_BOX),
            total_bantex: bantexList.length,
            status_submit: false,
            detail_bantex: bantexList,
            file_surat: selectedMainFile ? selectedMainFile.name : ''
        };

        $('#modalKonfirmasi').modal('hide');
        swal({ title: "Sukses!", text: "Data berhasil disubmit (Simulasi).", icon: "success", buttons: { confirm: { text: "OK", className: "btn btn-success" } } })
        .then(() => { window.location.href = "?module=barang-keluar"; });
    }
</script>