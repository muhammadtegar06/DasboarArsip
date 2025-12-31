<?php
// Mencegah direct access
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('location: 404.html');
} else {
    // Array Data Divisi
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

<style>
    .bg-gray-50 { background-color: #f9fafb; }
    .border-dashed { border-style: dashed; }
    .transition { transition: all 0.3s ease; }
    .bantex-item { border-left: 4px solid #1572e8; }
    .form-section { background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 8px; padding: 20px; }
</style>

<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-4">
        <div class="page-header text-white">
            <h4 class="page-title text-white"><i class="fas fa-archive mr-2"></i> Repository Arsip</h4>
        </div>
    </div>
</div>

<div class="page-inner mt--5">
    <div class="card">
        <div class="card-header">
            <div class="card-title">Kelola dokumen dan bantex arsip dengan mudah</div>
        </div>
        <div class="card-body">
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Divisi <span class="text-danger">*</span></label>
                        <select id="divisi" class="form-control select2" style="width:100%">
                            <option value="">-- Pilih Divisi --</option>
                            <?php foreach($divisi_list as $kode => $nama): ?>
                                <option value="<?= $kode ?>"><?= $kode ?> - <?= $nama ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Lokasi Arsip <span class="text-danger">*</span></label>
                        <select id="lokasi_arsip" class="form-control">
                            <option value="">-- Pilih Lokasi --</option>
                            <option value="HO">Head Office (HO)</option>
                        </select>
                    </div>
                </div>
            </div>

            <hr>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="font-weight-bold mb-0">Bantex & Dokumen</h4>
                <div class="text-right">
                    <div class="small text-muted">Total Bantex: <span class="font-weight-bold text-primary" id="countBantex">0</span></div>
                    <div class="small text-muted">Total Box: <span class="font-weight-bold text-success" id="countBox">0</span></div>
                </div>
            </div>

            <div id="boxListContainer" class="mb-3">
                <div class="alert alert-light border border-dashed text-center text-muted p-5">
                    <i class="fas fa-box-open fa-3x mb-3 text-gray-300"></i>
                    <p class="mb-0">Belum ada bantex ditambahkan</p>
                </div>
            </div>

            <button type="button" id="btnShowForm" class="btn btn-primary btn-block font-weight-bold py-2 mb-4" onclick="showInlineForm()">
                <i class="fas fa-plus mr-2"></i> Tambah Bantex
            </button>

            <div id="formInlineBantex" class="form-section mb-4 shadow-sm" style="display: none;">
                <h5 class="font-weight-bold border-bottom pb-2 mb-3">Form Bantex Baru</h5>
                
                <div class="form-group p-0 mb-3">
                    <label class="font-weight-bold">Nama Bantex <span class="text-danger">*</span></label>
                    <input type="text" id="inputNamaBantex" class="form-control" placeholder="Contoh: Bantex Kontrak 2023">
                </div>

                <div class="bg-white border rounded p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <label class="mb-0 font-weight-bold text-muted">Dokumen dalam Bantex <span class="text-danger">*</span></label>
                        <button type="button" class="btn btn-xs btn-secondary" onclick="addDocRow()">
                            <i class="fas fa-plus mr-1"></i> Tambah Dokumen
                        </button>
                    </div>

                    <div class="row mb-2 px-2 text-muted font-weight-bold" style="font-size: 11px; text-transform: uppercase;">
                        <div class="col-5">Nama Dokumen</div>
                        <div class="col-3">Bulan</div>
                        <div class="col-3">Tahun</div>
                        <div class="col-1"></div>
                    </div>

                    <div id="docRowsContainer">
                        </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <button type="button" class="btn btn-success btn-block font-weight-bold" onclick="saveInlineBantex()">
                            <i class="fas fa-check mr-2"></i> Simpan Bantex
                        </button>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn btn-secondary btn-block font-weight-bold" onclick="hideInlineForm()">
                            <i class="fas fa-times mr-2"></i> Batal
                        </button>
                    </div>
                </div>
            </div>

            <div class="border-top pt-4 row">
                <div class="col-md-6 mb-2">
                    <button type="button" onclick="handlePreSubmit()" class="btn btn-success btn-block btn-lg font-weight-bold shadow">
                        <i class="fas fa-paper-plane mr-2"></i> Submit Arsip
                    </button>
                </div>
                <div class="col-md-6 mb-2">
                    <a href="?module=barang_masuk" class="btn btn-info btn-block btn-lg font-weight-bold">
                        Lihat Data Surat Masuk
                    </a>
                </div>
            </div>

        </div>
        
        <div class="card-footer bg-blue-50" style="background-color: #e1f5fe;">
            <div class="d-flex align-items-start text-info">
                <i class="fas fa-info-circle mr-2 mt-1"></i>
                <small><strong>Informasi:</strong> Setiap kotak dapat menampung hingga 6 bantex. Sistem akan secara otomatis membuat kotak baru ketika jumlah bantex mencapai 6.</small>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalConfirm" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0 shadow-lg">
            
            <div class="modal-header bg-success text-white py-3">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-check-circle mr-2"></i> Konfirmasi Data Surat Masuk
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body p-4 bg-light">
                
                <div class="bg-white p-3 rounded shadow-sm mb-3">
                    <h6 class="font-weight-bold text-dark border-bottom pb-2 mb-3">Informasi Pengajuan</h6>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block font-weight-bold text-uppercase" style="font-size: 10px;">Divisi</small>
                            <span class="font-weight-bold text-dark" id="confDivisi">-</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block font-weight-bold text-uppercase" style="font-size: 10px;">Lokasi Arsip</small>
                            <span class="font-weight-bold text-dark" id="confLokasi">-</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block font-weight-bold text-uppercase" style="font-size: 10px;">Tanggal Pengajuan</small>
                            <span class="font-weight-bold text-dark"><?= date('Y-m-d') ?></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block font-weight-bold text-uppercase" style="font-size: 10px;">Total Bantex</small>
                            <span class="font-weight-bold text-dark"><span id="confTotalBantex">0</span> Bantex</span>
                        </div>
                    </div>
                </div>

                <h6 class="font-weight-bold text-dark mb-2">Daftar Dokumen</h6>
                <div id="confDocList" class="mb-3" style="max-height: 250px; overflow-y: auto;">
                    </div>

                <div class="alert alert-success border-success bg-white text-center shadow-sm">
                    <div class="row">
                        <div class="col-6 border-right border-success">
                            <small class="text-success font-weight-bold">TOTAL BANTEX</small>
                            <h2 class="font-weight-bold text-dark mb-0" id="confCountBantex">0</h2>
                        </div>
                        <div class="col-6">
                            <small class="text-success font-weight-bold">TOTAL BOX</small>
                            <h2 class="font-weight-bold text-dark mb-0" id="confCountBox">0</h2>
                        </div>
                    </div>
                    <div class="small text-success mt-2">(6 Bantex = 1 Box)</div>
                </div>

            </div>

            <div class="modal-footer bg-white">
                <button type="button" class="btn btn-secondary btn-round font-weight-bold" data-dismiss="modal">
                    Kembali Edit
                </button>
                <button type="button" onclick="submitFinal()" class="btn btn-success btn-round px-4 font-weight-bold shadow">
                    Konfirmasi & Submit
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Global State
    let bantexList = [];
    const MAX_PER_BOX = 6;

    $(document).ready(function() {
        $('.select2').select2();
    });

    // --- HELPER FUNCTION UNTUK DROPDOWN ---
    function getMonthOptions() {
        const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        let options = '<option value="">- Bulan -</option>';
        months.forEach(m => {
            options += `<option value="${m}">${m}</option>`;
        });
        return options;
    }

    function getYearOptions() {
        let currentYear = new Date().getFullYear();
        let options = '<option value="">- Tahun -</option>';
        for(let i = currentYear - 5; i <= currentYear + 5; i++) {
            let selected = (i === currentYear) ? 'selected' : '';
            options += `<option value="${i}" ${selected}>${i}</option>`;
        }
        return options;
    }

    // --- 1. LOGIKA INLINE FORM ---

    function showInlineForm() {
        $('#inputNamaBantex').val('');
        $('#docRowsContainer').empty();
        
        // Tambah 1 baris default
        addDocRow();
        
        $('#btnShowForm').hide();
        $('#formInlineBantex').fadeIn();
    }

    function hideInlineForm() {
        $('#formInlineBantex').hide();
        $('#btnShowForm').fadeIn();
    }

    // Tambah Baris Dokumen (2 Kolom Select: Bulan & Tahun)
    function addDocRow() {
        let html = `
        <div class="row mb-2 doc-row align-items-center">
            <div class="col-5 pr-1">
                <input type="text" class="form-control form-control-sm doc-name" placeholder="Nama Dokumen">
            </div>
            <div class="col-3 px-1">
                <select class="form-control form-control-sm doc-month">
                    ${getMonthOptions()}
                </select>
            </div>
            <div class="col-3 px-1">
                <select class="form-control form-control-sm doc-year">
                    ${getYearOptions()}
                </select>
            </div>
            <div class="col-1 pl-1 text-center">
                <button type="button" class="btn btn-xs btn-danger btn-round" onclick="removeRow(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>`;
        $('#docRowsContainer').append(html);
    }

    function removeRow(btn) {
        // Cek sisa baris
        if($('#docRowsContainer').children().length > 1) {
            $(btn).closest('.doc-row').remove();
        } else {
            alert("Minimal harus ada 1 dokumen!");
        }
    }

    function saveInlineBantex() {
        let nama = $('#inputNamaBantex').val();
        if(!nama.trim()) { alert("Nama Bantex wajib diisi"); return; }

        let docs = [];
        let valid = true;

        $('.doc-row').each(function() {
            let dName = $(this).find('.doc-name').val();
            let dMonth = $(this).find('.doc-month').val();
            let dYear = $(this).find('.doc-year').val();

            if(dName && dMonth && dYear) {
                // Format Periode: "Bulan Tahun" (Contoh: Januari 2025)
                let fullPeriod = dMonth + " " + dYear;
                docs.push({ name: dName, period: fullPeriod });
            } else if(dName || dMonth || dYear) {
                valid = false;
            }
        });

        if(!valid) { alert("Lengkapi nama dokumen, bulan, dan tahun!"); return; }
        if(docs.length === 0) { alert("Minimal isi 1 dokumen!"); return; }

        bantexList.push({
            id: Date.now(),
            nama_bantex: nama,
            dokumen: docs
        });

        renderBoxList();
        hideInlineForm();
    }

    // --- 2. RENDER LIST BOX ---

    function renderBoxList() {
        let container = $('#boxListContainer');
        container.empty();

        let totalBantex = bantexList.length;
        let totalBox = Math.ceil(totalBantex / MAX_PER_BOX);

        $('#countBantex').text(totalBantex);
        $('#countBox').text(totalBox);

        if(totalBantex === 0) {
            container.html(`
                <div class="alert alert-light border border-dashed text-center text-muted p-5">
                    <i class="fas fa-box-open fa-3x mb-3 text-gray-300"></i>
                    <p class="mb-0">Belum ada bantex ditambahkan</p>
                </div>`);
            return;
        }

        let boxCounter = 1;
        for(let i = 0; i < totalBantex; i += MAX_PER_BOX) {
            let chunk = bantexList.slice(i, i + MAX_PER_BOX);
            
            let htmlBox = `
            <div class="card mb-3 border border-primary bg-light">
                <div class="card-header py-2 bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary font-weight-bold">
                        <i class="fas fa-box mr-2"></i> Box ${boxCounter}
                    </h5>
                    ${chunk.length === 6 ? '<span class="badge badge-danger">Penuh</span>' : ''}
                </div>
                <div class="card-body p-3">
                    <div class="row">`;

            chunk.forEach((item, idx) => {
                let realIdx = i + idx;
                
                let docList = item.dokumen.map(d => 
                    `<li class="small text-muted">${d.name} <span class="text-secondary font-weight-bold">(${d.period})</span></li>`
                ).join("");

                htmlBox += `
                <div class="col-md-6 mb-2">
                    <div class="card h-100 shadow-sm border-0 bantex-item bg-white">
                        <div class="card-body p-2 position-relative">
                            <button class="btn btn-xs text-danger position-absolute" style="top:5px; right:5px;" onclick="removeBantex(${realIdx})">
                                <i class="fas fa-times"></i>
                            </button>
                            <h6 class="font-weight-bold text-dark mb-1" style="font-size:14px;">
                                Bantex ${idx + 1}: ${item.nama_bantex}
                            </h6>
                            <ul class="pl-3 mb-0" style="list-style-type: disc;">
                                ${docList}
                            </ul>
                        </div>
                    </div>
                </div>`;
            });

            htmlBox += `</div></div></div>`;
            container.append(htmlBox);
            boxCounter++;
        }
    }

    function removeBantex(index) {
        if(confirm("Hapus bantex ini?")) {
            bantexList.splice(index, 1);
            renderBoxList();
        }
    }

    // --- 3. SUBMIT & KONFIRMASI ---

    function handlePreSubmit() {
        let div = $('#divisi').val();
        let loc = $('#lokasi_arsip').val();

        if(!div) { alert("Pilih Divisi!"); return; }
        if(!loc) { alert("Pilih Lokasi Arsip!"); return; }
        if(bantexList.length === 0) { alert("Minimal 1 Bantex diperlukan!"); return; }

        $('#confDivisi').text(div);
        $('#confLokasi').text(loc);
        $('#confTotalBantex').text(bantexList.length);
        $('#confCountBantex').text(bantexList.length);
        $('#confCountBox').text(Math.ceil(bantexList.length / MAX_PER_BOX));

        let htmlDocs = '';
        bantexList.forEach((b, idx) => {
            let listD = b.dokumen.map(d => `<li>${d.name} <span class="text-muted">(${d.period})</span></li>`).join("");
            
            htmlDocs += `
            <div class="alert alert-primary bg-white border border-primary p-2 mb-2 rounded shadow-sm">
                <strong class="text-primary d-block mb-1">Bantex ${idx+1}: ${b.nama_bantex}</strong>
                <ul class="mb-0 pl-3 small text-dark">
                    ${listD}
                </ul>
            </div>`;
        });
        $('#confDocList').html(htmlDocs);

        $('#modalConfirm').modal('show');
    }

    function submitFinal() {
        let postData = {
            divisi: $('#divisi').val(),
            lokasi: $('#lokasi_arsip').val(),
            data_bantex: JSON.stringify(bantexList)
        };

        $.ajax({
            url: 'modules/barang-masuk/proses_simpan.php', 
            type: 'POST',
            data: postData,
            success: function(res) {
                alert("Sukses! Data berhasil disimpan.");
                window.location.href = "?module=barang_masuk";
            },
            error: function() {
                alert("Gagal menyimpan data.");
            }
        });
    }
</script>
<?php } ?>