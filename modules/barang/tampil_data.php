<?php
// Mencegah akses langsung
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('location: 404.html');
} else {
    ?>
    <style>
        .badge-rfid-empty {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 600;
        }

        .badge-rfid-filled {
            background-color: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
            font-family: monospace;
            font-size: 12px;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .box-info {
            font-weight: 600;
            color: #4b5563;
            background: #f3f4f6;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 12px;
        }

        .text-divisi {
            font-weight: 700;
            color: #1f2937;
        }

        /* Custom input disabled style agar terlihat jelas tidak bisa diedit tapi tetap rapi */
        .form-control:disabled,
        .form-control[readonly] {
            background-color: #f8fafc !important;
            opacity: 1;
            border-color: #e2e8f0;
            color: #64748b;
            font-weight: 600;
        }
    </style>

    <div class="panel-header bg-secondary-gradient">
        <div class="page-inner py-45">
            <div class="d-flex align-items-left align-items-md-top flex-column flex-md-row">
                <div class="page-header text-white">
                    <h4 class="page-title text-white"><i class="fas fa-search mr-2"></i> Monitoring Arsip Dokumen</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="page-inner mt--5">
        <div class="card border-0 shadow-sm rounded-lg">
            <div class="card-header bg-white border-bottom-0 pt-4">
                <h4 class="card-title font-weight-bold">Daftar Semua Dokumen Tersimpan</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th width="25%">Nama Dokumen</th>
                                <th width="15%" class="text-center">RFID Box</th>
                                <th width="15%">Bantex / Ordner</th>
                                <th width="20%">Divisi Pemilik</th>
                                <th width="15%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // QUERY COMPLEX JOIN 5 TABEL
                            $query = mysqli_query($mysqli, "
                                SELECT 
                                    doc.id AS id_dokumen,
                                    doc.nama_dokumen,
                                    doc.nomor_dokumen,
                                    doc.file_dokumen,
                                    b.nama_bantex,
                                    b.label_judul,
                                    box.kode_box, 
                                    box.rfid_code,
                                    box.lokasi_arsip,
                                    divisi.nama_divisi,
                                    divisi.singkatan_divisi
                                FROM tbl_dokumen doc
                                JOIN tbl_bantex b ON doc.id_bantex = b.id
                                JOIN tbl_box box ON b.id_box = box.id
                                JOIN tbl_pengajuan p ON box.id_pengajuan = p.id
                                JOIN tbl_divisi divisi ON p.id_divisi = divisi.id
                                ORDER BY doc.id DESC
                            ");

                            if (!$query) {
                                echo "<tr><td colspan='7' class='text-center text-danger'>Error Query: " . mysqli_error($mysqli) . "</td></tr>";
                            } else {
                                $no = 1;
                                while ($data = mysqli_fetch_assoc($query)) {
                                    $rfid = $data['rfid_code'];

                                    // Logika Tampilan RFID
                                    if (empty($rfid)) {
                                        $rfid_display = '<span class="badge-rfid-empty"><i class="fas fa-times-circle mr-1"></i>Belum Diinput</span>';
                                    } else {
                                        $rfid_display = '<span class="badge-rfid-filled"><i class="fas fa-barcode mr-1"></i>' . $rfid . '</span>';
                                    }

                                    // Path File
                                    $file_path = "uploads/dokumen/" . $data['file_dokumen'];

                                    // Data JSON untuk dikirim ke Modal Edit
                                    $row_data = [
                                        'id' => $data['id_dokumen'],
                                        'nama_dokumen' => $data['nama_dokumen'],
                                        'file_dokumen' => $data['file_dokumen'],
                                        'rfid' => empty($rfid) ? 'Belum Diinput' : $rfid,
                                        'bantex' => $data['nama_bantex'] . ' - ' . $data['label_judul'],
                                        'divisi' => $data['singkatan_divisi'] . ' (' . $data['nama_divisi'] . ')',
                                        'file_path' => file_exists($file_path) && !empty($data['file_dokumen']) ? $file_path : ''
                                    ];
                                    $jsonData = htmlspecialchars(json_encode($row_data), ENT_QUOTES, 'UTF-8');
                                    ?>
                                    <tr>
                                        <td class="text-center text-muted"><?= $no++; ?></td>

                                        <td>
                                            <div class="font-weight-bold text-dark"><?= $data['nama_dokumen'] ?></div>
                                            <?php if (!empty($data['nomor_dokumen'])) { ?>
                                                <div class="small text-muted"><i
                                                        class="fas fa-hashtag mr-1"></i><?= $data['nomor_dokumen'] ?></div>
                                            <?php } ?>
                                        </td>

                                        <td class="text-center">
                                            <?= $rfid_display ?>
                                        </td>

                                        <td>
                                            <div class="font-weight-bold text-primary"><?= $data['nama_bantex'] ?></div>
                                            <div class="small text-muted text-truncate" style="max-width: 150px;"
                                                title="<?= $data['label_judul'] ?>">
                                                <?= $data['label_judul'] ?>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="text-divisi"><?= $data['singkatan_divisi'] ?></div>
                                            <div class="small text-muted"><?= $data['nama_divisi'] ?></div>
                                        </td>

                                        <td class="text-center">
                                            <div class="d-flex justify-content-center" style="gap: 5px;">
                                                <button type="button" onclick="bukaModalEdit(<?= $jsonData ?>)"
                                                    class="btn btn-icon btn-round btn-warning btn-sm shadow-sm"
                                                    data-toggle="tooltip" title="Edit Dokumen">
                                                    <i class="fas fa-pen"></i>
                                                </button>

                                                <?php if (file_exists($file_path) && !empty($data['file_dokumen'])) { ?>
                                                    <a href="<?= $file_path ?>" target="_blank"
                                                        class="btn btn-icon btn-round btn-primary btn-sm shadow-sm"
                                                        data-toggle="tooltip" title="Lihat File Digital">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                <?php } else { ?>
                                                    <button class="btn btn-icon btn-round btn-secondary btn-sm shadow-sm" disabled
                                                        title="File Fisik Tidak Ada / Belum Diupload">
                                                        <i class="fas fa-eye-slash"></i>
                                                    </button>
                                                <?php } ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditDokumen" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0" style="border-radius: 15px; overflow: hidden;">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-white font-weight-bold"><i class="fas fa-edit mr-2"></i> Edit Data Dokumen
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <form id="formEditDokumen" enctype="multipart/form-data">
                        <input type="hidden" name="id_dokumen" id="edit_id">

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="small font-weight-bold text-muted">Divisi Pemilik</label>
                                <input type="text" id="edit_divisi" class="form-control form-control-sm" disabled>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-muted">Bantex & Label</label>
                                <input type="text" id="edit_bantex" class="form-control form-control-sm" disabled>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-muted">RFID Box</label>
                                <input type="text" id="edit_rfid" class="form-control form-control-sm" disabled>
                            </div>
                        </div>

                        <hr class="mt-2 mb-4">

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="font-weight-bold text-dark">Nama Dokumen <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="nama_dokumen" id="edit_nama" class="form-control" required
                                    placeholder="Masukkan Nama Dokumen...">
                            </div>

                            <div class="col-md-12 mb-2">
                                <label class="font-weight-bold text-dark">Ubah File Dokumen</label>
                                <input type="file" name="file_dokumen" id="edit_file" class="form-control bg-white"
                                    accept=".pdf,.png,.jpg,.jpeg">
                                <div class="mt-2" id="info_file_lama"></div>
                                <small class="text-warning font-weight-bold"><i class="fas fa-info-circle mr-1"></i> Biarkan
                                    kosong jika tidak ingin mengganti file yang sudah ada.</small>
                            </div>
                        </div>

                        <div class="mt-4 text-right">
                            <button type="button" class="btn btn-default btn-round mr-2" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning btn-round font-weight-bold shadow-sm"><i
                                    class="fas fa-save mr-2"></i> Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            // Re-inisialisasi tooltip saat pagination datatable berubah
            $('#basic-datatables').on('draw.dt', function () {
                $('[data-toggle="tooltip"]').tooltip();
            });
            $('[data-toggle="tooltip"]').tooltip();
        });

        // Buka Modal dan isi form
        function bukaModalEdit(data) {
            $('#edit_id').val(data.id);
            $('#edit_divisi').val(data.divisi);
            $('#edit_bantex').val(data.bantex);
            $('#edit_rfid').val(data.rfid);

            $('#edit_nama').val(data.nama_dokumen);
            $('#edit_file').val(''); // Reset file input

            // Tampilkan info file lama jika ada
            if (data.file_path !== '') {
                $('#info_file_lama').html(`<a href="${data.file_path}" target="_blank" class="badge badge-primary px-3 py-2"><i class="fas fa-file-alt mr-1"></i> Lihat File Saat Ini (${data.file_dokumen})</a>`);
            } else {
                $('#info_file_lama').html(`<span class="badge badge-secondary px-3 py-2"><i class="fas fa-times mr-1"></i> Belum ada file digital</span>`);
            }

            $('#modalEditDokumen').modal('show');
        }

        // Proses Submit Edit menggunakan AJAX
        $('#formEditDokumen').on('submit', function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            Swal.fire({
                title: 'Simpan Perubahan?',
                text: "Pastikan nama dan file dokumen sudah benar.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#fbd341', // Warna kuning template
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Menyimpan...', didOpen: () => { Swal.showLoading() } });

                    $.ajax({
                        // Ganti URL ini sesuai dengan path file proses update Anda
                        url: 'modules/barang/proses_ubah.php',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: function (resp) {
                            if (resp.status === 'success') {
                                Swal.fire({ icon: 'success', title: 'Berhasil!', text: resp.message, timer: 1500, showConfirmButton: false }).then(() => location.reload());
                            } else {
                                Swal.fire('Gagal!', resp.message, 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('Error', 'Terjadi kesalahan pada server saat mengunggah.', 'error');
                        }
                    });
                }
            });
        });
    </script>
<?php } ?>