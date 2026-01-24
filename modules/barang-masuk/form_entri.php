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
                        <form id="formPengajuan" enctype="multipart/form-data">

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-uppercase small text-dark">Divisi Pengaju <span
                                                class="text-danger">*</span></label>
                                        <select id="divisi" name="divisi"
                                            class="form-control select2-single font-weight-bold text-dark"
                                            style="height: 45px; style=" width:100%>
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

                            <div class="row mb-4 bg-light rounded p-3 mx-1 border">
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

                            <div class="row mb-5">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="font-weight-bold text-uppercase small text-dark">Upload Surat
                                            Pengantar / Memo <span class="text-danger">*</span></label>

                                        <div class="custom-file-upload text-center border p-4 rounded bg-white"
                                            style="border: 2px dashed #d1d3e2 !important; cursor: pointer;"
                                            onclick="document.getElementById('file_surat').click()">
                                            <input type="file" id="file_surat" name="file_surat"
                                                accept=".pdf,.jpg,.png,.jpeg" style="display: none;"
                                                onchange="previewFile()">

                                            <div id="uploadPlaceholder">
                                                <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                                                <h5 class="font-weight-bold text-dark">Klik untuk Upload Surat</h5>
                                                <p class="text-muted small mb-0">Format: PDF atau Gambar (JPG/PNG). Maksimal
                                                    5MB.</p>
                                            </div>

                                            <div id="fileInfo" style="display: none;">
                                                <i class="fas fa-file-pdf fa-3x text-danger mb-2"></i>
                                                <h5 class="font-weight-bold text-dark" id="fileName">Nama File.pdf</h5>
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-round mt-2"
                                                    onclick="resetFile(event)">Hapus File</button>
                                            </div>
                                        </div>
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

        // 2. Preview Nama File Upload
        function previewFile() {
            let input = document.getElementById('file_surat');
            if (input.files && input.files[0]) {
                let file = input.files[0];

                // Validasi Ukuran (Max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    Swal.fire('Gagal', 'Ukuran file terlalu besar (Max 5MB)', 'error');
                    input.value = '';
                    return;
                }

                document.getElementById('uploadPlaceholder').style.display = 'none';
                document.getElementById('fileInfo').style.display = 'block';
                document.getElementById('fileName').innerText = file.name;
            }
        }

        // 3. Reset File
        function resetFile(event) {
            event.stopPropagation(); // Mencegah trigger klik parent
            document.getElementById('file_surat').value = '';
            document.getElementById('uploadPlaceholder').style.display = 'block';
            document.getElementById('fileInfo').style.display = 'none';
        }

        // 4. Proses Submit dengan AJAX & SweetAlert2
        function submitPengajuan() {
            let form = document.getElementById('formPengajuan');
            let formData = new FormData(form);

            // Validasi Manual
            if (formData.get('divisi') === "") { Swal.fire('Peringatan', 'Pilih Divisi terlebih dahulu', 'warning'); return; }
            if (formData.get('jumlah_box') === "") { Swal.fire('Peringatan', 'Isi Jumlah Box', 'warning'); return; }
            if (document.getElementById('file_surat').files.length === 0) { Swal.fire('Peringatan', 'Upload Surat Pengantar wajib diisi', 'warning'); return; }

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