<?php
// Mencegah akses langsung
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('location: 404.html');
} else {
    // Tangkap Filter Divisi
    $selected_divisi = isset($_GET['filter_divisi']) ? mysqli_real_escape_string($mysqli, $_GET['filter_divisi']) : '';

    // Ambil List Divisi untuk Dropdown
    $divisi_query = mysqli_query($mysqli, "SELECT * FROM tbl_divisi ORDER BY nama_divisi ASC");
    $divisi_options = [];
    while ($d = mysqli_fetch_assoc($divisi_query)) {
        $divisi_options[] = $d;
    }

    // Query Utama Laporan (HANYA MENGAMBIL STATUS 'To Send')
    $where_clause = "WHERE p.status = 'To Send'";
    if ($selected_divisi != '') {
        $where_clause .= " AND d.singkatan_divisi = '$selected_divisi'";
    }

    $query_laporan = mysqli_query($mysqli, "
        SELECT 
            p.id, 
            p.no_pengajuan as id_transaksi, 
            p.tanggal_pengajuan, 
            p.jumlah_box as jml_box, 
            p.status,
            d.nama_divisi as divisi, 
            d.singkatan_divisi as kode_divisi,
            (SELECT COUNT(*) FROM tbl_bantex b JOIN tbl_box bx ON b.id_box = bx.id WHERE bx.id_pengajuan = p.id) as jml_bantex,
            (SELECT COUNT(*) FROM tbl_dokumen doc JOIN tbl_bantex b2 ON doc.id_bantex = b2.id JOIN tbl_box bx2 ON b2.id_box = bx2.id WHERE bx2.id_pengajuan = p.id) as total_dok,
            (SELECT rfid_code FROM tbl_box bx3 WHERE bx3.id_pengajuan = p.id AND rfid_code IS NOT NULL LIMIT 1) as rf_id,
            (SELECT h.waktu FROM tbl_history_pengiriman h JOIN tbl_pengiriman pg ON h.id_pengiriman = pg.id WHERE pg.id_pengajuan = p.id AND h.status = 'To Send' ORDER BY h.waktu DESC LIMIT 1) as tgl_to_send
        FROM tbl_pengajuan p
        JOIN tbl_divisi d ON p.id_divisi = d.id
        $where_clause
        ORDER BY p.id DESC
    ");

    $data_tampil = [];
    $total_dokumen = 0;
    $total_box_fisik = 0;

    while ($row = mysqli_fetch_assoc($query_laporan)) {
        $data_tampil[] = $row;
        $total_dokumen += (int) $row['total_dok'];
        $total_box_fisik += (int) $row['jml_box'];
    }
    ?>

    <style>
        /* --- STYLING RINGKAS DAN RAPI --- */
        .stat-box {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 20px;
            display: inline-flex;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
            margin-right: 15px;
            font-weight: bold;
            color: #334155;
        }

        .stat-box i {
            color: #3b82f6;
            font-size: 18px;
            margin-right: 10px;
        }

        .table-custom thead th {
            background-color: #f8fafc !important;
            color: #475569 !important;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0 !important;
            padding: 12px 15px !important;
        }

        .table-custom tbody td {
            vertical-align: middle;
            padding: 12px 15px !important;
            font-size: 13px;
            color: #1e293b;
            border-bottom: 1px solid #f1f5f9;
        }

        .badge-rfid {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            background: #f1f5f9;
            border: 1px dashed #cbd5e1;
            padding: 4px 8px;
            border-radius: 5px;
            color: #475569;
        }
    </style>

    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-4">
            <div class="d-flex align-items-left align-items-md-top flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold"><i class="fas fa-file-contract mr-2"></i> Laporan Arsip (To Send)
                    </h2>
                    <h5 class="text-white op-7 mb-2">Rekapitulasi seluruh dokumen fisik yang telah berstatus siap dikirim ke
                        Gudang.</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="page-inner mt--5">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-header bg-white pt-4 pb-3" style="border-radius: 12px 12px 0 0;">
                <div class="row align-items-center">
                    <div class="col-md-5 mb-3 mb-md-0">
                        <div class="d-flex">
                            <div class="stat-box">
                                <i class="fas fa-file-alt text-primary"></i>
                                <div><span class="text-muted d-block" style="font-size: 10px; line-height: 1;">Total
                                        Dokumen</span> <?= number_format($total_dokumen, 0, ',', '.') ?> File</div>
                            </div>
                            <div class="stat-box">
                                <i class="fas fa-box text-warning"></i>
                                <div><span class="text-muted d-block" style="font-size: 10px; line-height: 1;">Total
                                        Box</span> <?= number_format($total_box_fisik, 0, ',', '.') ?> Box</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7 text-md-right">
                        <form action="" method="GET" class="d-inline-block form-inline mr-2">
                            <input type="hidden" name="module"
                                value="<?= isset($_GET['module']) ? htmlspecialchars($_GET['module']) : 'laporan_arsip' ?>">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light font-weight-bold">Filter:</span>
                                </div>
                                <select name="filter_divisi" class="form-control form-control-sm"
                                    onchange="this.form.submit()" style="min-width: 180px;">
                                    <option value="">Semua Divisi</option>
                                    <?php foreach ($divisi_options as $d): ?>
                                        <option value="<?= $d['singkatan_divisi'] ?>"
                                            <?= ($selected_divisi == $d['singkatan_divisi']) ? 'selected' : ''; ?>>
                                            <?= $d['singkatan_divisi'] . ' - ' . $d['nama_divisi'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </form>

                        <div class="d-inline-block">
                            <button onclick="window.print()"
                                class="btn btn-dark btn-sm btn-round font-weight-bold mr-1 shadow-sm">
                                <i class="fas fa-print mr-1"></i> Cetak
                            </button>
                            <a href="modules/laporan-stok/export_excel.php?filter_divisi=<?= urlencode($selected_divisi) ?>"
                                target="_blank" class="btn btn-success btn-sm btn-round font-weight-bold shadow-sm">
                                <i class="fas fa-file-excel mr-1"></i> Export Excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-custom mb-0" id="laporan-datatables">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center pl-4">No</th>
                                <th width="12%">ID Transaksi</th>
                                <th width="15%">Divisi</th>
                                <th width="25%">Waktu Status "To Send"</th>
                                <th width="15%">RF ID</th>
                                <th width="13%" class="text-center">Isi Box</th>
                                <th width="15%" class="text-center pr-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (empty($data_tampil)) {
                                echo '<tr><td colspan="7" class="text-center py-4 text-muted font-italic">Tidak ada laporan dengan status To Send pada divisi ini.</td></tr>';
                            } else {
                                $no = 1;
                                foreach ($data_tampil as $row) {
                                    $rfid_text = $row['rf_id'] ? $row['rf_id'] : '-';

                                    // Format waktu status berubah menjadi To Send
                                    $tgl_to_send = !empty($row['tgl_to_send']) ? date('d M Y, H:i', strtotime($row['tgl_to_send'])) : '<i class="text-muted small">Belum Tercatat</i>';
                                    ?>
                                    <tr>
                                        <td class="text-center text-muted pl-4"><?= $no++; ?></td>
                                        <td><span class="font-weight-bold text-primary"><?= $row['id_transaksi'] ?></span></td>
                                        <td>
                                            <span class="font-weight-bold"><?= $row['kode_divisi'] ?></span><br>
                                            <span class="text-muted small">Aju:
                                                <?= date('d M Y', strtotime($row['tanggal_pengajuan'])) ?></span>
                                        </td>
                                        <td>
                                            <i class="far fa-clock text-warning mr-1"></i>
                                            <span class="font-weight-bold text-dark"><?= $tgl_to_send ?> WIB</span>
                                        </td>
                                        <td><span class="badge-rfid"><?= $rfid_text ?></span></td>
                                        <td class="text-center">
                                            <div class="badge badge-light border text-dark">
                                                <?= $row['jml_box'] ?> Box | <?= $row['jml_bantex'] ?> Btx
                                            </div>
                                        </td>
                                        <td class="text-center pr-4">
                                            <span class="badge badge-success px-3 py-1 font-weight-bold shadow-sm"
                                                style="border-radius: 20px;">
                                                <i class="fas fa-truck mr-1"></i> <?= strtoupper($row['status']) ?>
                                            </span>
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
            <div class="card-footer bg-white border-top text-center py-3" style="border-radius: 0 0 12px 12px;">
                <small class="text-muted"><i class="fas fa-info-circle mr-1"></i> Tabel ini bersifat read-only dan hanya
                    merangkum data arsip yang siap kirim.</small>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#laporan-datatables').DataTable({
                "pageLength": 10,
                "ordering": false,
                "info": true,
                "lengthChange": false,
                "language": {
                    "search": "Cari Cepat:",
                    "zeroRecords": "Tidak ada data yang cocok",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ laporan",
                    "infoEmpty": "Menampilkan 0 laporan",
                    "paginate": {
                        "previous": "<i class='fas fa-angle-left'></i>",
                        "next": "<i class='fas fa-angle-right'></i>"
                    }
                }
            });
        });
    </script>
<?php } ?>