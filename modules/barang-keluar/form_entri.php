<?php
// Mencegah akses langsung
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('location: 404.html');
}

// 1. Ambil ID Pengajuan dari URL
$id_pengajuan = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// 2. Ambil Data Header Pengajuan
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
        /* Agar footer rapi */
    }

    .box-header-area {
        background: #f8fafc;
        padding: 15px 20px;
        border-bottom: 1px solid #e2e8f0;
    }

    .box-footer-area {
        background: #fff7ed;
        /* Warna agak oranye/kuning lembut untuk highlight */
        padding: 20px;
        border-top: 1px dashed #fdba74;
    }

    .bantex-row:hover {
        background-color: #f8fafc;
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

    /* Styling khusus input RFID agar menonjol */
    .input-rfid {
        height: 45px;
        font-size: 16px;
        letter-spacing: 1px;
        border: 2px solid #e2e8f0;
        background-color: #fff;
    }

    .input-rfid:focus {
        border-color: #f97316;
        /* Orange focus */
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
    }
</style>

<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-4">
        <div class="d-flex align-items-left align-items-md-top flex-column flex-md-row">
            <div>
                <h2 class="text-white pb-2 fw-bold"><i class="fas fa-file-upload mr-2"></i> Input Dokumen & Fisik</h2>
                <h5 class="text-white op-7 mb-2">Lengkapi dokumen digital per bantex, lalu scan RFID box di akhir.</h5>
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
                        <h6 class="mb-0 font-weight-bold text-muted">Isi detail dokumen bantex di bawah ini</h6>
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

                                // Cek jumlah file
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
                                            placeholder="Contoh: Voucher Januari 2025" value="<?= $bantex['label_judul'] ?>">
                                    </td>
                                    <td class="py-3">
                                        <input type="text" name="ket[<?= $id_bantex ?>]" class="input-clean text-muted"
                                            placeholder="Keterangan tambahan..." value="">
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
                    <div class="row align-items-center">
                        <div class="col-md-7">
                            <h6 class="font-weight-bold text-warning mb-1"><i class="fas fa-barcode mr-2"></i>Langkah
                                Terakhir: Identifikasi Fisik</h6>
                            <small class="text-muted">Setelah semua dokumen dan bantex di atas terisi, tempelkan stiker RFID
                                pada Box fisik lalu scan kodenya di kolom ini.</small>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-white"><i class="fas fa-wifi text-warning"></i></span>
                                </div>
                                <input type="text" name="rfid[<?= $id_box ?>]"
                                    class="form-control input-rfid font-weight-bold text-dark"
                                    placeholder="Klik & Scan RFID Box Disini..." value="<?= $box['rfid_code'] ?>">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        <?php } ?>
    </form>
</div>

<div class="modal fade" id="modalDokumen" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-folder-open mr-2"></i> Kelola Dokumen</h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <input type="hidden" id="modal_id_bantex">
                <div class="mb-3">
                    <h6 class="font-weight-bold text-dark mb-1" id="modal_nama_bantex">-</h6>
                    <small class="text-muted">Upload file digital (PDF/Scan) ke dalam bantex ini.</small>
                </div>

                <div class="card shadow-sm border mb-3">
                    <div class="card-body">
                        <form id="formUploadDokumen" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" name="nama_dokumen" class="form-control form-control-sm mb-2"
                                        placeholder="Nama Dokumen" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="tahun_dokumen" class="form-control form-control-sm mb-2"
                                        placeholder="Tahun" value="<?= date('Y') ?>">
                                </div>
                                <div class="col-md-3">
                                    <input type="file" name="file_dokumen" class="form-control-file small" required
                                        accept=".pdf,.jpg,.png">
                                </div>
                                <div class="col-md-2 text-right">
                                    <button type="submit" class="btn btn-sm btn-primary btn-block"><i
                                            class="fas fa-upload"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div id="listDokumenArea"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // JS Logic sama seperti sebelumnya, hanya menyesuaikan flow visual
    function simpanSemuaData() {
        Swal.fire({
            title: 'Simpan Data Arsip?',
            text: "Pastikan Label Bantex dan RFID Box sudah sesuai.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan',
            confirmButtonColor: '#28a745'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Menyimpan...', didOpen: () => { Swal.showLoading() } });
                $.ajax({
                    url: 'modules/pengisian-box/proses_update_data.php',
                    type: 'POST',
                    data: $('#formInputData').serialize(),
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            Swal.fire('Berhasil!', 'Data arsip dan RFID tersimpan.', 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Gagal!', response.message, 'error');
                        }
                    },
                    error: function () { Swal.fire('Error', 'Server Error', 'error'); }
                });
            }
        });
    }

    function kelolaDokumen(idBantex, namaBantex) {
        $('#modal_id_bantex').val(idBantex);
        $('#modal_nama_bantex').text(namaBantex);
        $('#modalDokumen').modal('show');
        loadListDokumen(idBantex);
    }

    function loadListDokumen(idBantex) {
        $.get('modules/pengisian-box/get_dokumen_list.php?id_bantex=' + idBantex, function (html) {
            $('#listDokumenArea').html(html);
        });
    }

    $('#formUploadDokumen').on('submit', function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        formData.append('id_bantex', $('#modal_id_bantex').val());

        $.ajax({
            url: 'modules/pengisian-box/proses_upload_dokumen.php',
            type: 'POST',
            data: formData,
            contentType: false, processData: false,
            dataType: 'json',
            success: function (resp) {
                if (resp.status === 'success') {
                    $('#formUploadDokumen')[0].reset();
                    loadListDokumen($('#modal_id_bantex').val());
                    const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
                    Toast.fire({ icon: 'success', title: 'File diupload' });
                } else { Swal.fire('Gagal', resp.message, 'error'); }
            }
        });
    });

    function hapusDokumen(id) {
        if (confirm('Hapus dokumen ini?')) {
            $.post('modules/pengisian-box/proses_hapus_dokumen.php', { id: id }, function (r) {
                loadListDokumen($('#modal_id_bantex').val());
            });
        }
    }
</script>