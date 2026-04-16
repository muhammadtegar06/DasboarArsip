<?php
// Mencegah akses langsung
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('location: 404.html');
}

$id_pengajuan = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$q_header = mysqli_query($mysqli, "
    SELECT p.*, d.nama_divisi, d.singkatan_divisi 
    FROM tbl_pengajuan p
    JOIN tbl_divisi d ON p.id_divisi = d.id
    WHERE p.id = '$id_pengajuan' AND (p.status = 'Disetujui' OR p.status = 'Diterima')
");
$header = mysqli_fetch_assoc($q_header);

if (!$header) {
    echo "<script>alert('Data tidak ditemukan atau status belum disetujui!'); window.location='?module=pengisian_data_box';</script>";
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
        transition: border-color 0.3s;
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

    .img-preview-box {
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 4px;
        background: #fff;
        display: inline-block;
        transition: all 0.3s;
    }

    .img-preview-box:hover {
        border-color: #3b82f6;
        transform: scale(1.05);
    }
</style>

<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-4">
        <div class="d-flex align-items-left align-items-md-top flex-column flex-md-row">
            <div>
                <h2 class="text-white pb-2 fw-bold"><i class="fas fa-file-upload mr-2"></i> Input Dokumen & Fisik</h2>
                <h5 class="text-white op-7 mb-2">Lengkapi dokumen, label bantex, RFID Box.</h5>
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
                    <a href="modules/barang-keluar/export_serah_terima.php?id=<?= $id_pengajuan ?>" target="_blank"
                        class="btn btn-primary btn-lg btn-round shadow font-weight-bold mr-2">
                        <i class="fas fa-file-excel mr-2"></i> BUAT DOKUMEN SERAH TERIMA
                    </a>
                    <button type="button" onclick="simpanSemuaData()"
                        class="btn btn-success btn-lg btn-round shadow font-weight-bold">
                        <i class="fas fa-save mr-2"></i> SIMPAN SEMUA DATA
                    </button>
                </div>
            </div>
        </div>
    </div>

    <form id="formInputData" enctype="multipart/form-data">
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
                                placeholder="Lokasi Rak (Cth: A-01)"
                                value="<?= htmlspecialchars($box['lokasi_arsip'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                    </div>
                </div>

                <div class="p-0">
                    <table class="table table-sm mb-0 table-hover">
                        <thead class="bg-light text-muted">
                            <tr>
                                <th width="5%" class="text-center pl-3">No</th>
                                <th width="20%">Kode Bantex</th>
                                <th width="55%">Label / Judul Arsip <span class="text-danger">*</span></th>
                                <th width="20%" class="text-center pr-3">File Digital</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $q_bantex = mysqli_query($mysqli, "SELECT * FROM tbl_bantex WHERE id_box = '$id_box' ORDER BY id ASC");
                            $no_bantex = 1;
                            while ($bantex = mysqli_fetch_assoc($q_bantex)) {
                                $id_bantex = $bantex['id'];
                                $q_doc = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT COUNT(*) as jml FROM tbl_dokumen WHERE id_bantex='$id_bantex'"));
                                $jml_doc = $q_doc['jml'];

                                $btn_class = ($jml_doc > 0) ? "btn-info" : "btn-outline-secondary";
                                $btn_text = ($jml_doc > 0) ? '<i class="fas fa-check-circle mr-1"></i> ' . $jml_doc . ' File' : '<i class="fas fa-pen mr-1"></i> Entri Dokumen';

                                // PEMBERSIHAN STRING: Ditambahkan fallback "?? ''" agar tidak error jika NULL
                                $label_asli = $bantex['label_judul'] ?? '';
                                $label_bersih = preg_replace('/\s+/', ' ', $label_asli);
                                $label_bersih = htmlspecialchars(trim($label_bersih), ENT_QUOTES, 'UTF-8');
                                ?>
                                <tr class="bantex-row">
                                    <td class="text-center py-3 pl-3 text-muted"><?= $no_bantex++ ?></td>
                                    <td class="py-3 font-weight-bold text-dark"><?= $bantex['nama_bantex'] ?></td>
                                    <td class="py-3">
                                        <input type="text" name="judul[<?= $id_bantex ?>]"
                                            class="input-clean font-weight-bold input-wajib" placeholder="Ketik Label Judul..."
                                            value="<?= $label_bersih ?>" required>
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
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h6 class="font-weight-bold text-warning mb-0"><i class="fas fa-barcode mr-2"></i>Identifikasi
                                Fisik Box</h6>
                            <small class="text-muted">Ketik kode RFID yang tertempel di Box.</small>
                        </div>
                        <div style="width: 300px;">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white"><i class="fas fa-wifi text-warning"></i></span>
                                </div>
                                <input type="text" name="rfid[<?= $id_box ?>]" class="form-control input-rfid"
                                    placeholder="Input RFID disini..."
                                    value="<?= htmlspecialchars($box['rfid_code'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                    autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-start justify-content-between pt-3"
                        style="border-top: 1px dashed #fdba74;">
                        <div>
                            <h6 class="font-weight-bold text-info mb-0"><i class="fas fa-camera mr-2"></i>Foto Fisik Box
                            </h6>
                            <small class="text-muted">Upload foto kondisi box saat ini.</small>
                        </div>
                        <div style="width: 300px;">
                            <?php if (!empty($box['foto_box'])): ?>
                                <div class="d-flex align-items-center mb-2 bg-white p-2 border rounded">
                                    <a href="uploads/box/<?= $box['foto_box'] ?>" target="_blank" class="img-preview-box mr-2">
                                        <img src="uploads/box/<?= $box['foto_box'] ?>" alt="Foto Box"
                                            style="width: 40px; height: 40px; object-fit: cover;">
                                    </a>
                                    <div style="line-height: 1.2;">
                                        <span class="d-block text-success font-weight-bold" style="font-size: 11px;"><i
                                                class="fas fa-check-circle"></i> Foto Tersimpan</span>
                                        <a href="uploads/box/<?= $box['foto_box'] ?>" target="_blank" class="text-primary"
                                            style="font-size: 11px;">Lihat Ukuran Penuh</a>
                                    </div>
                                </div>
                                <input type="file" name="foto_box[<?= $id_box ?>]" class="form-control form-control-sm bg-white"
                                    accept="image/*" title="Upload foto baru jika ingin mengganti">
                                <small class="text-warning mt-1 d-block font-weight-bold"><i class="fas fa-info-circle"></i>
                                    Pilih file baru jika ingin mengganti foto lama.</small>
                            <?php else: ?>
                                <input type="file" name="foto_box[<?= $id_box ?>]" class="form-control form-control-sm bg-white"
                                    accept="image/*">
                                <small class="text-muted mt-1 d-block">Belum ada foto. (Opsional)</small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </form>
</div>

<div class="modal fade" id="modalDokumen" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 90% !important;">
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
    function getHtmlBaris() {
        return `
        <div class="card p-3 mb-2 border input-row" style="background-color: #f8fafc;">
            <div class="row">
                <div class="col-md-5 mb-2">
                    <label class="small font-weight-bold text-muted">Nama Dokumen <span class="text-danger">*</span></label>
                    <input type="text" name="nama_dokumen[]" class="form-control form-control-sm" placeholder="Contoh: SK Pengangkatan" required>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted">Nomor Dokumen <span class="text-danger">*</span></label>
                    <input type="text" name="nomor_dokumen[]" class="form-control form-control-sm" placeholder="No. Surat / Arsip" required>
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small font-weight-bold text-muted">Tahun <span class="text-danger">*</span></label>
                    <input type="number" name="tahun_dokumen[]" class="form-control form-control-sm" value="<?= date('Y') ?>" required>
                </div>
                <div class="col-md-1 mb-2 text-right">
                     <label class="small text-white">Del</label>
                     <button type="button" class="btn btn-sm btn-icon btn-danger btn-round shadow-sm" onclick="hapusBaris(this)" title="Hapus Baris"><i class="fas fa-times"></i></button>
                </div>
            </div>
            <div class="row align-items-center mt-2">
                <div class="col-md-6">
                    <label class="small font-weight-bold text-muted">Keterangan Tambahan (Opsional)</label>
                    <input type="text" name="keterangan[]" class="form-control form-control-sm" placeholder="Keterangan dokumen...">
                </div>
                <div class="col-md-6">
                    <label class="small font-weight-bold text-muted">File Dokumen (Opsional)</label>
                    <input type="file" name="file_dokumen[]" class="form-control form-control-sm bg-white" accept=".pdf,.jpg,.png">
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
        $('#dynamicInputArea').html(getHtmlBaris());
        $('#modalDokumen').modal('show');
        loadListDokumen(idBantex);
    }

    function loadListDokumen(idBantex) {
        $.get('modules/barang-keluar/dokumen/get_dokumen_list.php?id_bantex=' + idBantex, function (html) {
            $('#listDokumenArea').html(html);
        });
    }

    $('#formUploadDokumen').on('submit', function (e) {
        e.preventDefault();
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
                    $('#dynamicInputArea').html(getHtmlBaris());
                    loadListDokumen($('#modal_id_bantex').val());
                    Swal.fire({ icon: 'success', title: 'Tersimpan!', text: resp.message, timer: 1500, showConfirmButton: false });
                } else { Swal.fire('Gagal', resp.message, 'error'); }
            },
            error: function () { btn.html(htmlBtn).prop('disabled', false); Swal.fire('Error', 'Server Error', 'error'); }
        });
    });

    function simpanSemuaData() {
        let formValid = true;
        $('.input-wajib').each(function () {
            if ($(this).val().trim() === '') {
                formValid = false;
                $(this).css('border', '2px solid #ef4444');
            } else {
                $(this).css('border', '1px solid #cbd5e1');
            }
        });

        if (!formValid) {
            Swal.fire({ icon: 'warning', title: 'Data Belum Lengkap!', text: 'Harap isi Label/Judul Arsip pada Bantex yang ditandai dengan garis merah.' });
            return;
        }

        Swal.fire({
            title: 'Simpan Data Box?', text: "Pastikan label bantex, lokasi rak, dan foto (jika ada) sudah terisi.",
            icon: 'question', showCancelButton: true, confirmButtonText: 'Ya, Simpan', confirmButtonColor: '#28a745'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Menyimpan...', didOpen: () => { Swal.showLoading() } });

                let formElement = document.getElementById('formInputData');
                let formData = new FormData(formElement);

                $.ajax({
                    url: 'modules/barang-keluar/dokumen/proses_update_data.php',
                    type: 'POST', data: formData, contentType: false, processData: false, dataType: 'json',
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

    function hapusDokumen(id) {
        Swal.fire({
            title: 'Hapus Dokumen?', text: "Data dan file dokumen ini akan dihapus secara permanen.",
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, didOpen: () => { Swal.showLoading() } });
                $.ajax({
                    url: 'modules/barang-keluar/dokumen/proses_hapus_dokumen.php',
                    type: 'POST', data: { id: id }, dataType: 'json',
                    success: function (res) {
                        if (res.status === 'success') {
                            Swal.fire({ icon: 'success', title: 'Terhapus!', text: res.message, timer: 1500, showConfirmButton: false });
                            loadListDokumen($('#modal_id_bantex').val());
                        } else { Swal.fire('Gagal!', res.message, 'error'); }
                    },
                    error: function () { Swal.fire('Error', 'Terjadi kesalahan server.', 'error'); }
                });
            }
        });
    }
</script>