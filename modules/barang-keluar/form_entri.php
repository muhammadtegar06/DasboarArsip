<?php
// Mencegah akses langsung
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('location: 404.html');
}

// 1. Ambil ID Pengajuan dari URL
$id_pengajuan = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// 2. Ambil Data Header Pengajuan (Hanya yang Disetujui/Diterima)
$q_header = mysqli_query($mysqli, "
    SELECT p.*, d.nama_divisi, d.singkatan_divisi 
    FROM tbl_pengajuan p
    JOIN tbl_divisi d ON p.id_divisi = d.id
    WHERE p.id = '$id_pengajuan' AND (p.status = 'Disetujui' OR p.status = 'Diterima')
");
$header = mysqli_fetch_assoc($q_header);

// Jika data tidak ditemukan
if (!$header) {
    echo "<script>alert('Data tidak ditemukan atau status belum disetujui!'); window.location='?module=pengisian_data_box';</script>";
    exit;
}
?>

<style>
    /* Styling Kartu Box */
    .box-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        margin-bottom: 30px;
        background: #fff;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
        overflow: hidden;
    }

    .box-header-area {
        background: #f8fafc;
        padding: 15px 20px;
        border-bottom: 1px solid #e2e8f0;
    }

    .box-footer-area {
        background: #f0fdf4;
        /* Hijau lembut */
        padding: 15px 20px;
        border-top: 1px dashed #bbf7d0;
    }

    .bantex-row:hover {
        background-color: #f8fafc;
    }

    /* Input Style Clean */
    .input-clean {
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 6px 10px;
        width: 100%;
        font-size: 13px;
    }

    .input-clean:focus {
        border-color: #3b82f6;
        outline: none;
    }
</style>

<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-4">
        <div class="d-flex align-items-left align-items-md-top flex-column flex-md-row">
            <div>
                <h2 class="text-white pb-2 fw-bold"><i class="fas fa-file-upload mr-2"></i> Input Dokumen & Bantex</h2>
                <h5 class="text-white op-7 mb-2">Lengkapi detail dokumen dan judul bantex. RFID diisi nanti.</h5>
            </div>
            <div class="ml-md-auto py-2 py-md-0">
                <a href="?module=pengisian_data_box" class="btn btn-white btn-border btn-round">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-inner mt--5">

    <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <small class="text-uppercase text-muted font-weight-bold">Divisi Pengaju</small>
                    <h4 class="font-weight-bold mb-0 text-dark"><?= $header['nama_divisi'] ?></h4>
                    <div class="mt-1"><span class="badge badge-light border"><?= $header['no_pengajuan'] ?></span></div>
                </div>
                <div class="col-md-6 text-right">
                    <button type="button" onclick="simpanSemuaData()"
                        class="btn btn-success btn-lg btn-round shadow font-weight-bold">
                        <i class="fas fa-save mr-2"></i> SIMPAN SEMUA DATA
                    </button>
                </div>
            </div>
        </div>
    </div>

    <form id="formInputData">
        <input type="hidden" name="id_pengajuan" value="<?= $id_pengajuan ?>">

        <?php
        // Loop Box
        $q_box = mysqli_query($mysqli, "SELECT * FROM tbl_box WHERE id_pengajuan = '$id_pengajuan' ORDER BY id ASC");
        $no_box = 1;

        while ($box = mysqli_fetch_assoc($q_box)) {
            $id_box = $box['id'];
            ?>
            <div class="box-card">

                <div class="box-header-area d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <span class="badge badge-primary mr-3" style="font-size:14px; padding: 8px 15px;">BOX
                            <?= $no_box++ ?></span>
                        <h6 class="mb-0 font-weight-bold text-muted">Detail Bantex & Dokumen</h6>
                    </div>
                    <div style="width: 250px;">
                        <div class="input-group input-group-sm">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white border-right-0"><i
                                        class="fas fa-map-marker-alt text-danger"></i></span>
                            </div>
                            <input type="text" name="lokasi[<?= $id_box ?>]" class="form-control border-left-0"
                                placeholder="Lokasi Rak (Cth: A-01)" value="<?= $box['lokasi_arsip'] ?>">
                        </div>
                    </div>
                </div>

                <div class="p-0">
                    <table class="table table-sm mb-0 table-hover">
                        <thead class="bg-light text-muted">
                            <tr>
                                <th width="5%" class="text-center pl-3">No</th>
                                <th width="20%">Kode Bantex</th>
                                <th width="35%">Label / Judul Arsip <span class="text-danger">*</span></th>
                                <th width="25%">Keterangan</th>
                                <th width="15%" class="text-center pr-3">File Digital</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Loop Bantex dalam Box ini
                            $q_bantex = mysqli_query($mysqli, "SELECT * FROM tbl_bantex WHERE id_box = '$id_box' ORDER BY id ASC");
                            $no_bantex = 1;
                            while ($bantex = mysqli_fetch_assoc($q_bantex)) {
                                $id_bantex = $bantex['id'];

                                // Cek jumlah file dokumen yang sudah ada
                                $q_doc = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT COUNT(*) as jml FROM tbl_dokumen WHERE id_bantex='$id_bantex'"));
                                $jml_doc = $q_doc['jml'];

                                // Tombol berubah warna jika sudah ada file
                                $btn_class = ($jml_doc > 0) ? "btn-info" : "btn-outline-secondary";
                                $btn_text = ($jml_doc > 0) ? '<i class="fas fa-check-circle mr-1"></i> ' . $jml_doc . ' File' : '<i class="fas fa-upload mr-1"></i> Upload';
                                ?>
                                <tr class="bantex-row">
                                    <td class="text-center py-3 pl-3 text-muted"><?= $no_bantex++ ?></td>
                                    <td class="py-3 font-weight-bold text-dark"><?= $bantex['nama_bantex'] ?></td>
                                    <td class="py-3">
                                        <input type="text" name="judul[<?= $id_bantex ?>]" class="input-clean font-weight-bold"
                                            placeholder="Input Label disini..." value="<?= $bantex['label_judul'] ?>">
                                    </td>
                                    <td class="py-3">
                                        <input type="text" name="ket[<?= $id_bantex ?>]" class="input-clean text-muted"
                                            placeholder="Keterangan..." value="">
                                    </td>
                                    <td class="text-center py-3 pr-3">
                                        <button type="button"
                                            onclick="kelolaDokumen(<?= $id_bantex ?>, '<?= $bantex['nama_bantex'] ?>')"
                                            class="btn btn-sm <?= $btn_class ?> btn-round shadow-sm" style="font-size: 11px;">
                                            <?= $btn_text ?>
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div class="box-footer-area">
                    <div class="d-flex align-items-center text-success">
                        <i class="fas fa-info-circle fa-2x mr-3"></i>
                        <div>
                            <h6 class="font-weight-bold mb-0">Informasi RFID</h6>
                            <small class="text-dark op-7">
                                Kode RFID akan diisi di akhir setelah fisik barang diterima dan disimpan oleh
                                <b>INDOARSIP</b>.
                            </small>
                        </div>
                    </div>

                    <input type="hidden" name="rfid[<?= $id_box ?>]" value="<?= $box['rfid_code'] ?>">
                </div>

            </div>
        <?php } ?>
    </form>
</div>

<div class="modal fade" id="modalDokumen" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 80% !important;">
        <div class="modal-content border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-folder-open mr-2"></i> Kelola Dokumen</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <input type="hidden" id="modal_id_bantex">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="font-weight-bold text-dark mb-0" id="modal_nama_bantex">-</h6>
                        <small class="text-muted">Isi detail dokumen sesuai arsip fisik.</small>
                    </div>
                </div>

                <div class="card shadow-sm border mb-4">
                    <div class="card-body p-3">
                        <form id="formUploadDokumen" enctype="multipart/form-data">

                            <div id="dynamicInputArea">
                            </div>

                            <div class="mt-3 d-flex justify-content-between">
                                <button type="button" class="btn btn-sm btn-secondary btn-round"
                                    onclick="tambahBaris()">
                                    <i class="fas fa-plus mr-1"></i> Tambah Baris
                                </button>
                                <button type="submit" class="btn btn-sm btn-success btn-round px-4 font-weight-bold">
                                    <i class="fas fa-save mr-1"></i> Simpan Semua Dokumen
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <h6 class="font-weight-bold text-dark mb-2"><i class="fas fa-history mr-2"></i>Riwayat Dokumen</h6>
                <div id="listDokumenArea"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // --- TEMPLATE BARIS INPUT (2 Baris per Item) ---
    function getHtmlBaris() {
        return `
        <div class="card p-3 mb-2 border input-row" style="background-color: #f8f9fa;">
            <div class="row">
                <div class="col-md-5 mb-2">
                    <label class="small font-weight-bold text-muted">Nama Dokumen <span class="text-danger">*</span></label>
                    <input type="text" name="nama_dokumen[]" class="form-control form-control-sm" placeholder="Contoh: SK Pengangkatan" required>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted">Nomor Dokumen</label>
                    <input type="text" name="nomor_dokumen[]" class="form-control form-control-sm" placeholder="No. Surat / Arsip">
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small font-weight-bold text-muted">Tahun</label>
                    <input type="number" name="tahun_dokumen[]" class="form-control form-control-sm" value="<?= date('Y') ?>" placeholder="YYYY">
                </div>
                <div class="col-md-1 mb-2 text-right">
                     <label class="small text-white">Del</label>
                     <button type="button" class="btn btn-sm btn-icon btn-danger btn-round shadow-sm" onclick="hapusBaris(this)" title="Hapus Baris">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-md-6">
                    <input type="text" name="keterangan[]" class="form-control form-control-sm" placeholder="Keterangan tambahan (Opsional)...">
                </div>
                <div class="col-md-6">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Upload File</span>
                        </div>
                        <input type="file" name="file_dokumen[]" class="form-control" required accept=".pdf,.jpg,.png">
                    </div>
                </div>
            </div>
        </div>`;
    }

    // Fungsi Tambah Baris
    function tambahBaris() {
        $('#dynamicInputArea').append(getHtmlBaris());
    }

    // Fungsi Hapus Baris
    function hapusBaris(btn) {
        // Cek sisa baris, jangan hapus jika tinggal satu
        if ($('#dynamicInputArea .input-row').length > 1) {
            $(btn).closest('.input-row').remove();
        } else {
            // Jika tinggal 1, cukup reset isinya
            $(btn).closest('.input-row').find('input').val('');
            $(btn).closest('.input-row').find('input[type="number"]').val('<?= date('Y') ?>');
        }
    }

    // Buka Modal
    function kelolaDokumen(idBantex, namaBantex) {
        $('#modal_id_bantex').val(idBantex);
        $('#modal_nama_bantex').text(namaBantex);

        // Reset Form ke 1 baris awal
        $('#dynamicInputArea').html(getHtmlBaris());

        $('#modalDokumen').modal('show');
        loadListDokumen(idBantex);
    }

    // Load List (Helper)
    function loadListDokumen(idBantex) {
        $.get('modules/pengisian-box/get_dokumen_list.php?id_bantex=' + idBantex, function (html) {
            $('#listDokumenArea').html(html);
        });
    }

    // --- PROSES UPLOAD (AJAX) ---
    $('#formUploadDokumen').on('submit', function (e) {
        e.preventDefault();

        // Validasi Sederhana
        let hasFile = false;
        $('input[name="file_dokumen[]"]').each(function () {
            if ($(this).val()) hasFile = true;
        });
        if (!hasFile) {
            Swal.fire('Peringatan', 'Mohon pilih minimal satu file.', 'warning');
            return;
        }

        let formData = new FormData(this);
        formData.append('id_bantex', $('#modal_id_bantex').val());

        // Loading
        let btn = $(this).find('button[type="submit"]');
        let htmlBtn = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);

        $.ajax({
            url: 'modules/barang-keluar/dokumen/proses_upload_dokumen.php',
            type: 'POST',
            data: formData,
            contentType: false, processData: false, dataType: 'json',
            success: function (resp) {
                btn.html(htmlBtn).prop('disabled', false);

                if (resp.status === 'success') {
                    // Reset ke 1 baris kosong
                    $('#dynamicInputArea').html(getHtmlBaris());
                    loadListDokumen($('#modal_id_bantex').val());

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: resp.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Gagal', resp.message, 'error');
                }
            },
            error: function () {
                btn.html(htmlBtn).prop('disabled', false);
                Swal.fire('Error', 'Terjadi kesalahan server', 'error');
            }
        });
    });

    // --- SIMPAN DATA UTAMA (TOMBOL HIJAU DI ATAS) ---
    function simpanSemuaData() {
        Swal.fire({
            title: 'Simpan Data Utama?',
            text: "Pastikan label bantex & lokasi rak sudah terisi.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan',
            confirmButtonColor: '#28a745'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Menyimpan...', didOpen: () => { Swal.showLoading() } });
                $.ajax({
                    url: 'modules/barang-keluar/dokumen/proses_update_data.php',
                    type: 'POST',
                    data: $('#formInputData').serialize(),
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            Swal.fire('Berhasil!', 'Data header tersimpan.', 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Gagal!', response.message, 'error');
                        }
                    },
                    error: function () { Swal.fire('Error', 'Kesalahan Server', 'error'); }
                });
            }
        });
    }

    // Hapus Dokumen Existing
    function hapusDokumen(id) {
        if (confirm('Hapus dokumen ini dari database?')) {
            $.post('modules/barang-keluar/dokumen/proses_hapus_dokumen.php', { id: id }, function (r) {
                loadListDokumen($('#modal_id_bantex').val());
            });
        }
    }
</script>