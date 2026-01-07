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

    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-4">
            <div class="page-header text-white">
                <h4 class="page-title text-white"><i class="fas fa-archive mr-2"></i> Pengajuan Box Arsip</h4>
            </div>
        </div>
    </div>

    <div class="page-inner mt--5">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Formulir Entri Box & Bantex</div>
            </div>
            <div class="card-body">

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Divisi <span class="text-danger">*</span></label>
                            <select id="divisi" class="form-control select2" style="width:100%">
                                <option value="">-- Pilih Divisi --</option>
                                <?php foreach ($divisi_list as $kode => $nama): ?>
                                    <option value="<?= $kode ?>"><?= $kode ?> - <?= $nama ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Lokasi Arsip <span class="text-danger">*</span></label>
                            <select id="lokasi_arsip" class="form-control">
                                <option value="Head Office ( HO )" selected>Head Office ( HO )</option>
                            </select>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Jumlah Box <span class="text-danger">*</span></label>
                            <select id="jumlah_box" class="form-control" onchange="hitungBantex()">
                                <option value="0">-- Pilih Jumlah Box --</option>
                                <?php for($i=1; $i<=10; $i++): ?>
                                    <option value="<?= $i ?>"><?= $i ?> Box</option>
                                <?php endfor; ?>
                            </select>
                            <small class="text-muted">Maksimal pengajuan 10 Box sekaligus.</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Total Bantex (Estimasi) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" id="jumlah_bantex" class="form-control font-weight-bold text-primary" value="0" readonly style="background-color: #f8f9fa;">
                                <div class="input-group-append">
                                    <span class="input-group-text">Bantex</span>
                                </div>
                            </div>
                            <small class="text-info font-italic">
                                <i class="fas fa-info-circle"></i> Otomatis terhitung: 1 Box = 6 Bantex
                            </small>
                        </div>
                    </div>
                </div>

                <div class="border-top pt-4 row">
                    <div class="col-md-6 mb-2">
                        <button type="button" onclick="submitSimpel()" class="btn btn-success btn-block btn-lg font-weight-bold shadow">
                            <i class="fas fa-paper-plane mr-2"></i> Simpan Data
                        </button>
                    </div>
                    <div class="col-md-6 mb-2">
                        <a href="?module=barang_masuk" class="btn btn-secondary btn-block btn-lg font-weight-bold">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Inisialisasi Select2 untuk Divisi
            $('.select2').select2();
        });

        // Fungsi Hitung Otomatis
        function hitungBantex() {
            // Ambil nilai jumlah box
            let box = document.getElementById('jumlah_box').value;
            
            // Konversi ke integer, default 0 jika kosong
            box = parseInt(box) || 0;
            
            // Rumus: 1 Box = 6 Bantex
            let totalBantex = box * 6;
            
            // Update field Bantex
            document.getElementById('jumlah_bantex').value = totalBantex;
        }

        // Fungsi Submit Data
        function submitSimpel() {
            // Ambil Value
            let divisi = $('#divisi').val();
            let lokasi = $('#lokasi_arsip').val(); // Akan otomatis "Head Office ( HO )"
            let box = $('#jumlah_box').val();
            let bantex = $('#jumlah_bantex').val();

            // Validasi Sederhana
            if (divisi === "") {
                alert("Harap pilih Divisi!");
                return;
            }
            // Validasi lokasi sebenarnya tidak perlu lagi karena sudah default selected, tapi dijaga untuk keamanan
            if (lokasi.trim() === "") {
                alert("Harap pilih Lokasi Arsip!");
                return;
            }
            if (box == 0 || box === "") {
                alert("Harap pilih Jumlah Box!");
                return;
            }

            // Konfirmasi User
            let konfirmasi = confirm(`Apakah data sudah benar?\n\nDivisi: ${divisi}\nLokasi: ${lokasi}\nJumlah: ${box} Box (${bantex} Bantex)`);
            
            if (konfirmasi) {
                // AJAX Request ke backend
                $.ajax({
                    url: 'modules/barang-masuk/proses_simpan.php',
                    type: 'POST',
                    data: {
                        divisi: divisi,
                        lokasi: lokasi,
                        jumlah_box: box,
                        jumlah_bantex: bantex
                    },
                    success: function (res) {
                        // Sesuaikan handling response dengan backend kamu
                        alert("Data berhasil disimpan!");
                        window.location.href = "?module=barang_masuk";
                    },
                    error: function () {
                        alert("Terjadi kesalahan saat menyimpan data.");
                    }
                });
            }
        }
    </script>

<?php } ?>