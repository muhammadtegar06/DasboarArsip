<?php
// Mencegah akses langsung
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('location: 404.html');
}

// 1. Ambil ID Pengajuan dari URL
$id_pengajuan = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// 2. Ambil Data Header
$q_header = mysqli_query($mysqli, "
    SELECT p.*, d.nama_divisi, d.singkatan_divisi 
    FROM tbl_pengajuan p
    JOIN tbl_divisi d ON p.id_divisi = d.id
    WHERE p.id = '$id_pengajuan' AND (p.status = 'Disetujui' OR p.status = 'Diterima')
");
$header = mysqli_fetch_assoc($q_header);

if (!$header) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='?module=pengisian_data_box';</script>";
    exit;
}
?>

<style>
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

    /* Update Footer Area untuk Input RFID */
    .box-footer-area {
        background: #fff7ed;
        padding: 15px 20px;
        border-top: 1px dashed #fdba74;
    }

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

    .input-rfid {
        font-weight: bold;
        color: #c2410c;
        letter-spacing: 1px;
    }
</style>

<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-4">
        <div class="d-flex align-items-left align-items-md-top flex-column flex-md-row">
            <div>
                <h2 class="text-white pb-2 fw-bold"><i class="fas fa-file-upload mr-2"></i> Input Dokumen & Fisik</h2>
                <h5 class="text-white op-7 mb-2">Lengkapi dokumen, label bantex, dan Scan RFID Box.</h5>
            </div>
            <div class="ml-md-auto py-2 py-md-0">
                <a href="?module=barang_keluar" class="btn btn-white btn-border btn-round">
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
                        <h6 class="mb-0 font-weight-bold text-muted">Detail Isi Box</h6>
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
                            $q_bantex = mysqli_query($mysqli, "SELECT * FROM tbl_bantex WHERE id_box = '$id_box' ORDER BY id ASC");
                            $no_bantex = 1;
                            while ($bantex = mysqli_fetch_assoc($q_bantex)) {
                                $id_bantex = $bantex['id'];
                                // Hitung jumlah dokumen existing
                                $q_doc = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT COUNT(*) as jml FROM tbl_dokumen WHERE id_bantex='$id_bantex'"));
                                $jml_doc = $q_doc['jml'];

                                $btn_class = ($jml_doc > 0) ? "btn-info" : "btn-outline-secondary";
                                $btn_text = ($jml_doc > 0) ? '<i class="fas fa-check-circle mr-1"></i> ' . $jml_doc . ' File' : '<i class="fas fa-upload mr-1"></i> Upload';
                                ?>
                                <tr class="bantex-row">
                                    <td class="text-center py-3 pl-3 text-muted"><?= $no_bantex++ ?></td>
                                    <td class="py-3 font-weight-bold text-dark"><?= $bantex['nama_bantex'] ?></td>
                                    <td class="py-3">
                                        <input type="text" name="judul[<?= $id_bantex ?>]" class="input-clean font-weight-bold"
                                            placeholder="Label Judul..." value="<?= $bantex['label_judul'] ?>">
                                    </td>
                                    <td class="py-3">
                                        <input type="text" class="input-clean text-muted" placeholder="Ket..." disabled
                                            style="background: #f1f1f1;" title="Kolom keterangan bantex belum ada di DB">
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
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="font-weight-bold text-warning mb-0"><i class="fas fa-barcode mr-2"></i>Identifikasi
                                Fisik Box</h6>
                            <small class="text-muted">Scan atau ketik kode RFID yang tertempel di Box.</small>
                        </div>
                        <div style="width: 300px;">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white"><i class="fas fa-wifi text-warning"></i></span>
                                </div>
                                <input type="text" name="rfid[<?= $id_box ?>]" class="form-control input-rfid"
                                    placeholder="Scan RFID disini..." value="<?= $box['rfid_code'] ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>
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
                            <div id="dynamicInputArea"></div>
                            <div class="mt-3 d-flex justify-content-between">
                                <button type="button" class="btn btn-sm btn-secondary btn-round"
                                    onclick="tambahBaris()">
                                    <i class="fas fa-plus mr-1"></i> Tambah Baris
                                </button>
                                <button type="submit" class="btn btn-sm btn-success btn-round px-4 font-weight-bold">
                                    <i class="fas fa-save mr-1"></i> Simpan Dokumen
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="section-title mb-2 font-weight-bold text-primary"><i class="fas fa-list-alt mr-2"></i>Daftar
                    Dokumen Tersimpan</div>
                <div id="listDokumenArea">
                    <div class="text-center py-5"><i class="fas fa-spinner fa-spin"></i> Memuat data...</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // --- TEMPLATE BARIS INPUT ---
    function getHtmlBaris() {
        return `
        <div class="card p-3 mb-2 border input-row" style="background-color: #fff;">
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
                    <input type="number" name="tahun_dokumen[]" class="form-control form-control-sm" value="<?= date('Y') ?>">
                </div>
                <div class="col-md-1 mb-2 text-right">
                     <label class="small text-white">Del</label>
                     <button type="button" class="btn btn-sm btn-icon btn-danger btn-round shadow-sm" onclick="hapusBaris(this)"><i class="fas fa-times"></i></button>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-md-6">
                    <input type="text" name="keterangan[]" class="form-control form-control-sm" placeholder="Keterangan tambahan...">
                </div>
                <div class="col-md-6">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend"><span class="input-group-text">File</span></div>
                        <input type="file" name="file_dokumen[]" class="form-control" required accept=".pdf,.jpg,.png">
                    </div>
                </div>
            </div>
        </div>`;
    }

    function tambahBaris() { $('#dynamicInputArea').append(getHtmlBaris()); }

    function hapusBaris(btn) {
        if ($('#dynamicInputArea .input-row').length > 1) { $(btn).closest('.input-row').remove(); }
        else { $(btn).closest('.input-row').find('input').val(''); }
    }

    function kelolaDokumen(idBantex, namaBantex) {
        $('#modal_id_bantex').val(idBantex);
        $('#modal_nama_bantex').text(namaBantex);
        $('#dynamicInputArea').html(getHtmlBaris()); // Reset input form
        $('#modalDokumen').modal('show');
        loadListDokumen(idBantex); // Load preview tabel
    }

    // LOAD PREVIEW DOKUMEN (AJAX)
    function loadListDokumen(idBantex) {
        $.get('modules/barang-keluar/dokumen/get_dokumen_list.php?id_bantex=' + idBantex, function (html) {
            $('#listDokumenArea').html(html);
        });
    }

    // UPLOAD DOKUMEN
    $('#formUploadDokumen').on('submit', function (e) {
        e.preventDefault();

        // Validasi File minimal 1
        let hasFile = false;
        $('input[name="file_dokumen[]"]').each(function () { if ($(this).val()) hasFile = true; });
        if (!hasFile) { Swal.fire('Peringatan', 'Pilih minimal satu file.', 'warning'); return; }

        let formData = new FormData(this);
        formData.append('id_bantex', $('#modal_id_bantex').val());
        let btn = $(this).find('button[type="submit"]');
        let htmlBtn = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin"></i> Loading...').prop('disabled', true);

        $.ajax({
            url: 'modules/barang-keluar/dokumen/proses_upload_dokumen.php',
            type: 'POST', data: formData, contentType: false, processData: false, dataType: 'json',
            success: function (resp) {
                btn.html(htmlBtn).prop('disabled', false);
                if (resp.status === 'success') {
                    $('#dynamicInputArea').html(getHtmlBaris()); // Reset input
                    loadListDokumen($('#modal_id_bantex').val()); // Refresh Preview
                    Swal.fire({ icon: 'success', title: 'Tersimpan!', text: resp.message, timer: 1500, showConfirmButton: false });
                } else { Swal.fire('Gagal', resp.message, 'error'); }
            },
            error: function () { btn.html(htmlBtn).prop('disabled', false); Swal.fire('Error', 'Server Error', 'error'); }
        });
    });

    // SIMPAN UTAMA (RFID & LABEL)
    function simpanSemuaData() {
        Swal.fire({
            title: 'Simpan Data?', text: "Pastikan RFID dan Label sudah benar.", icon: 'question',
            showCancelButton: true, confirmButtonText: 'Ya, Simpan', confirmButtonColor: '#28a745'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Menyimpan...', didOpen: () => { Swal.showLoading() } });
                $.ajax({
                    url: 'modules/barang-keluar/dokumen/proses_update_data.php',
                    type: 'POST', data: $('#formInputData').serialize(), dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            Swal.fire('Berhasil!', 'Data tersimpan.', 'success').then(() => location.reload());
                        } else { Swal.fire('Gagal!', response.message, 'error'); }
                    },
                    error: function () { Swal.fire('Error', 'Server Error', 'error'); }
                });
            }
        });
    }

    function hapusDokumen(id) {
        if (confirm('Hapus dokumen ini?')) {
            $.post('modules/barang-keluar/dokumen/proses_hapus_dokumen.php', { id: id }, function (r) {
                loadListDokumen($('#modal_id_bantex').val());
            });
        }
    }
</script>