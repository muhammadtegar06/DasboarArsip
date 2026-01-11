<?php
// Mencegah direct access
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('location: 404.html');
} else {
    // PENTING: Panggil koneksi database di sini untuk mengisi dropdown
    // Sesuaikan path jika error
    // require_once "config/database.php"; 
    // (Asumsi: config sudah dipanggil di file induk/main.php yang me-load file ini)
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
                                <?php 
                                // UPDATE: Mengambil data Divisi langsung dari Database
                                $query_divisi = mysqli_query($mysqli, "SELECT * FROM tbl_divisi ORDER BY nama_divisi ASC");
                                while ($div = mysqli_fetch_assoc($query_divisi)) {
                                    // Value menggunakan singkatan agar sesuai logika backend
                                    echo '<option value="'.$div['singkatan_divisi'].'">'.$div['singkatan_divisi'].' - '.$div['nama_divisi'].'</option>';
                                }
                                ?>
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
            $('.select2').select2();
        });

        function hitungBantex() {
            let box = document.getElementById('jumlah_box').value;
            box = parseInt(box) || 0;
            let totalBantex = box * 6;
            document.getElementById('jumlah_bantex').value = totalBantex;
        }

        function submitSimpel() {
            let divisi = $('#divisi').val();
            let lokasi = $('#lokasi_arsip').val();
            let box = $('#jumlah_box').val();
            let bantex = $('#jumlah_bantex').val();

            if (divisi === "") { alert("Harap pilih Divisi!"); return; }
            if (box == 0 || box === "") { alert("Harap pilih Jumlah Box!"); return; }

            // Loading state (biar user ga klik berkali-kali)
            let btn = $('button[onclick="submitSimpel()"]');
            let originalText = btn.html();
            btn.attr('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

            $.ajax({
                url: 'modules/barang-masuk/proses_simpan.php',
                type: 'POST',
                data: {
                    divisi: divisi,
                    lokasi: lokasi,
                    jumlah_box: box,
                    jumlah_bantex: bantex
                },
                dataType: 'json', // Memberitahu jQuery bahwa respon server adalah JSON
                success: function (res) {
                    if (res.status === 'success') {
                        // Gunakan SweetAlert jika ada, atau alert biasa
                        alert(res.message); 
                        window.location.href = "?module=barang_masuk";
                    } else {
                        alert("Gagal: " + res.message);
                        btn.attr('disabled', false).html(originalText);
                    }
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText); // Cek console browser F12 jika error
                    alert("Terjadi kesalahan sistem. Cek Console.");
                    btn.attr('disabled', false).html(originalText);
                }
            });
        }
    </script>

<?php } ?>