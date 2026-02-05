<?php
// Mencegah akses langsung
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('location: 404.html');
} else {
    ?>

    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-4">
            <div class="d-flex align-items-left align-items-md-top flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold"><i class="fas fa-file-import mr-2"></i>Pengajuan Box Arsip</h2>
                    <h5 class="text-white op-7 mb-2">Formulir pengajuan penyimpanan box baru ke gudang arsip.</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="page-inner mt--5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm border-0" style="border-radius: 15px;">
                    <div class="card-header bg-white pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="card-title font-weight-bold text-dark">Formulir Pengajuan</h4>
                                <small class="text-muted">Isi detail pengajuan di bawah ini</small>
                            </div>
                            <div class="d-none d-md-block">
                                <span class="badge badge-primary px-3 py-2">Draft Pengajuan</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <form id="formPengajuan">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-uppercase small text-dark">Divisi Pengaju <span
                                                class="text-danger">*</span></label>
                                        <select id="divisi" name="divisi"
                                            class="form-control select2-single font-weight-bold text-dark"
                                            style="height: 45px; width:100%">
                                            <option value="">-- Pilih Divisi --</option>
                                            <?php
                                            // AMBIL DATA REAL DARI DATABASE
                                            $query_divisi = mysqli_query($mysqli, "SELECT * FROM tbl_divisi ORDER BY nama_divisi ASC");
                                            while ($div = mysqli_fetch_assoc($query_divisi)) {
                                                echo '<option value="' . $div['singkatan_divisi'] . '">' . $div['singkatan_divisi'] . ' - ' . $div['nama_divisi'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-uppercase small text-dark">Lokasi Penyimpanan
                                            <span class="text-danger">*</span></label>
                                        <select id="lokasi_arsip" name="lokasi" class="form-control">
                                            <option value="Head Office (HO)" selected>Head Office (HO)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-5 bg-light rounded p-3 mx-1 border">
                                <div class="col-md-6 border-right">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-uppercase small text-primary">Jumlah Box
                                            Diajukan <span class="text-danger">*</span></label>
                                        <select id="jumlah_box" name="jumlah_box"
                                            class="form-control select2-single font-weight-bold text-dark"
                                            style="height: 45px;" onchange="hitungEstimasi()">
                                            <option value="">-- Pilih Jumlah --</option>
                                            <?php for ($i = 1; $i <= 20; $i++): ?>
                                                <option value="<?= $i ?>"><?= $i ?> Box</option>
                                            <?php endfor; ?>
                                        </select>
                                        <small class="text-muted mt-2 d-block">Maksimal 20 Box per pengajuan.</small>
                                    </div>
                                </div>

                                <div class="col-md-6 d-flex align-items-center justify-content-center">
                                    <div class="text-center w-100">
                                        <label class="font-weight-bold text-uppercase small text-success">Estimasi Total
                                            Bantex</label>
                                        <h2 class="font-weight-bold text-success mb-0" id="displayBantex">0</h2>
                                        <small class="text-success font-weight-bold">(1 Box = 6 Bantex)</small>
                                        <input type="hidden" id="estimasi_bantex" name="estimasi_bantex" value="0">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <button type="button" onclick="submitPengajuan()"
                                        class="btn btn-success btn-lg btn-block font-weight-bold shadow-sm">
                                        <i class="fas fa-paper-plane mr-2"></i> Kirim Pengajuan
                                    </button>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <a href="?module=barang_masuk"
                                        class="btn btn-secondary btn-lg btn-block font-weight-bold">
                                        <i class="fas fa-times mr-2"></i> Batal
                                    </a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2({ theme: "bootstrap" });
        });

        // 1. Hitung Otomatis Estimasi Bantex
        function hitungEstimasi() {
            let box = document.getElementById('jumlah_box').value;
            let bantex = 0;

            if (box !== "") {
                bantex = parseInt(box) * 6; // Rumus: 1 Box = 6 Bantex
            }

            document.getElementById('displayBantex').innerText = bantex;
            document.getElementById('estimasi_bantex').value = bantex;
        }

        // FUNGSI PREVIEW FILE & RESET FILE DIHAPUS

        // 2. Proses Submit dengan AJAX & SweetAlert2
        function submitPengajuan() {
            let form = document.getElementById('formPengajuan');
            let formData = new FormData(form);

            // Validasi Manual
            if (formData.get('divisi') === "") { Swal.fire('Peringatan', 'Pilih Divisi terlebih dahulu', 'warning'); return; }
            if (formData.get('jumlah_box') === "") { Swal.fire('Peringatan', 'Isi Jumlah Box', 'warning'); return; }

            // VALIDASI FILE SURAT SUDAH DIHAPUS

            // Konfirmasi
            Swal.fire({
                title: 'Kirim Pengajuan?',
                text: "Pastikan data sudah benar. Detail isi bantex akan diisi setelah disetujui.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Kirim!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {

                    // Loading State
                    Swal.fire({
                        title: 'Mengirim Data...',
                        html: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading() }
                    });

                    // AJAX Request
                    $.ajax({
                        url: 'modules/barang-masuk/proses_simpan.php', // File backend
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: function (response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: response.message,
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href = '?module=barang_masuk';
                                });
                            } else {
                                Swal.fire('Gagal!', response.message, 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('Error', 'Terjadi kesalahan server.', 'error');
                        }
                    });
                }
            });
        }
    </script>
<?php } ?>