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
                                <th width="10%" class="text-center">Box</th>
                                <th width="15%">Bantex / Ordner</th>
                                <th width="20%">Divisi Pemilik</th>
                                <th width="10%" class="text-center">Aksi</th>
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
                                    box.kode_box, -- Sebenarnya field ini kosong di DB (sesuai gambar), kita pakai urutan saja nanti
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

                                    // Box & Bantex Display
                                    // Karena di DB tbl_box field kode_box kosong, kita gunakan Lokasi Arsip atau ID Box sbg identitas
                                    $box_display = !empty($data['lokasi_arsip']) ? $data['lokasi_arsip'] : "Box #Unknown";

                                    // Path File
                                    $file_path = "uploads/dokumen/" . $data['file_dokumen'];
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

                                        <td class="text-center">
                                            <span class="box-info"><?= $box_display ?></span>
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
                                            <?php if (file_exists($file_path) && !empty($data['file_dokumen'])) { ?>
                                                <a href="<?= $file_path ?>" target="_blank"
                                                    class="btn btn-icon btn-round btn-primary btn-sm" data-toggle="tooltip"
                                                    title="Lihat File">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            <?php } else { ?>
                                                <button class="btn btn-icon btn-round btn-secondary btn-sm" disabled
                                                    title="File Fisik Tidak Ada">
                                                    <i class="fas fa-eye-slash"></i>
                                                </button>
                                            <?php } ?>
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

    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
<?php } ?>