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

    // Query Utama Laporan (Real DB)
    $where = "";
    if ($selected_divisi != '') {
        $where = "WHERE d.singkatan_divisi = '$selected_divisi'";
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
            (SELECT nama_dokumen FROM tbl_dokumen d2 JOIN tbl_bantex b3 ON d2.id_bantex = b3.id JOIN tbl_box bx4 ON b3.id_box = bx4.id WHERE bx4.id_pengajuan = p.id LIMIT 1) as dokumen_utama
        FROM tbl_pengajuan p
        JOIN tbl_divisi d ON p.id_divisi = d.id
        $where
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
        /* --- GLOBAL LAYOUT --- */
        .main-panel>.content {
            padding: 0 !important;
        }

        .page-inner {
            padding: 25px 30px;
            width: 100%;
            max-width: 100%;
        }

        /* SEARCH & HERO */
        .hero-section {
            background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
            padding: 60px 20px 80px;
            border-radius: 0 0 30px 30px;
            color: white;
            text-align: center;
            margin-bottom: -50px;
        }

        .search-card-container {
            max-width: 95%;
            margin: 0 auto 40px;
            position: relative;
            z-index: 10;
        }

        .search-card {
            background: white;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            display: flex;
            gap: 15px;
            align-items: flex-end;
        }

        /* TOOLBAR */
        .toolbar-clean {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .stat-badge {
            background: white;
            padding: 10px 20px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 700;
            color: #1f2937;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            margin-right: 15px;
            display: inline-flex;
            align-items: center;
            transition: transform 0.2s;
        }

        .stat-badge:hover {
            transform: translateY(-2px);
        }

        .stat-badge i {
            color: #4f46e5;
            margin-right: 8px;
            font-size: 1.1rem;
        }

        /* TABLE STYLES */
        .card-table {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #f3f4f6;
        }

        .table-elegant {
            width: 100%;
            border-collapse: collapse;
        }

        .table-elegant th {
            padding: 18px 20px;
            text-align: left;
            font-size: 0.8rem;
            text-transform: uppercase;
            color: #6b7280;
            background: #f9fafb;
            border-bottom: 2px solid #f3f4f6;
        }

        .table-elegant td {
            padding: 18px 20px;
            border-bottom: 1px solid #f3f4f6;
            color: #1f2937;
            vertical-align: middle;
        }

        .table-elegant tr {
            cursor: pointer;
            transition: all 0.2s;
        }

        .table-elegant tr:hover {
            background-color: #f0f9ff;
            transform: scale(1.002);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            z-index: 5;
            position: relative;
        }

        /* STATUS BADGES */
        .status-badge {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            display: inline-block;
            text-align: center;
            width: 100%;
        }

        .st-terkirim {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .st-progres {
            background: #e0f2fe;
            color: #0284c7;
            border: 1px solid #bae6fd;
        }

        .st-pending {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        .status-desc {
            font-size: 0.65rem;
            display: block;
            font-weight: 500;
            margin-top: 2px;
            text-transform: capitalize;
        }

        /* MODAL & TABS */
        .modal-card-header {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            padding: 25px;
            border-radius: 15px 15px 0 0;
        }

        .nav-tabs-custom {
            display: flex;
            background: white;
            padding: 0 25px;
            border-bottom: 1px solid #e2e8f0;
        }

        .nav-item-custom {
            padding: 15px 20px;
            font-weight: 600;
            color: #64748b;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: 0.3s;
        }

        .nav-item-custom.active {
            color: #2563eb;
            border-bottom-color: #2563eb;
        }

        .tab-pane {
            display: none;
            padding: 25px;
        }

        .tab-pane.active {
            display: block;
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* TIMELINE */
        .t-item {
            position: relative;
            padding-bottom: 25px;
            padding-left: 30px;
            border-left: 2px solid #e2e8f0;
        }

        .t-item:last-child {
            border-left-color: transparent;
        }

        .t-icon {
            position: absolute;
            left: -11px;
            top: 0;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #3b82f6;
            border: 2px solid white;
        }
    </style>

    <div class="hero-section">
        <h1 style="font-weight:800;">Laporan Penyimpanan Arsip</h1>
        <p style="opacity:0.9;">Rekapitulasi Penyimpanan Dokumen Fisik</p>
    </div>

    <div class="search-card-container">
        <form action="" method="GET" style="width:100%;">
            <input type="hidden" name="module" value="laporan_arsip">
            <div class="search-card">
                <div style="flex-grow:1;">
                    <label class="small font-weight-bold text-muted">FILTER DIVISI</label>
                    <select name="filter_divisi" class="form-control form-control-lg" style="background:#f9fafb;">
                        <option value="">-- Semua Divisi --</option>
                        <?php foreach ($divisi_options as $d): ?>
                            <option value="<?= $d['singkatan_divisi'] ?>" <?= ($selected_divisi == $d['singkatan_divisi']) ? 'selected' : ''; ?>>
                                <?= $d['singkatan_divisi'] . ' - ' . $d['nama_divisi'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-dark btn-lg" style="border-radius:10px;"><i class="fas fa-search"></i>
                    Cari</button>
            </div>
        </form>
    </div>

    <div class="page-inner mt--5">
        <div class="toolbar-clean">
            <div class="d-flex flex-wrap align-items-center">
                <div class="stat-badge"><i class="fas fa-file-alt"></i> <?= $total_dokumen ?> Dokumen</div>
                <div class="stat-badge"><i class="fas fa-box"></i> <?= $total_box_fisik ?> Box Fisik</div>
            </div>

            <div class="btn-group">
                <button onclick="window.print()" class="btn btn-outline-dark btn-round btn-sm font-weight-bold mr-2">
                    <i class="fas fa-print mr-1"></i> Cetak PDF
                </button>
                <a href="modules/laporan-stok/export_excel.php?filter_divisi=<?= $selected_divisi ?>" target="_blank"
                    class="btn btn-success btn-round btn-sm font-weight-bold shadow-sm">
                    <i class="fas fa-file-excel mr-1"></i> Export Excel (Sesuai Format)
                </a>
            </div>
        </div>

        <div class="card-table">
            <div class="table-responsive">
                <table class="table-elegant" id="tblArsip">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="15%">ID Transaksi</th>
                            <th width="20%">Divisi</th>
                            <th width="20%">Sample Dokumen</th>
                            <th width="12%">RF ID</th>
                            <th width="8%" class="text-center">Jml Bantex</th>
                            <th width="8%" class="text-center">Jml Box</th>
                            <th width="12%" class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (empty($data_tampil)) {
                            echo '<tr><td colspan="8" class="text-center py-5 text-muted">Data tidak ditemukan</td></tr>';
                        } else {
                            $no = 1;
                            foreach ($data_tampil as $row) {
                                $stClass = 'st-pending';
                                $stDesc = 'Menunggu Proses';

                                $status = strtolower($row['status']);
                                if (strpos($status, 'terkirim') !== false || strpos($status, 'serah terima') !== false) {
                                    $stClass = 'st-terkirim';
                                    $stDesc = 'Selesai';
                                } elseif (strpos($status, 'siap') !== false || strpos($status, 'disetujui') !== false) {
                                    $stClass = 'st-progres';
                                    $stDesc = 'Diproses';
                                }

                                // Siapkan data JSON untuk Modal
                                $row['dokumen_utama'] = $row['dokumen_utama'] ?: 'Belum ada dokumen';
                                $row['rf_id'] = $row['rf_id'] ?: 'Belum Scan';
                                $jsonData = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                                ?>
                                <tr onclick="openModal(<?= $jsonData ?>)">
                                    <td class="text-center font-weight-bold text-muted"><?= $no++; ?></td>
                                    <td><span
                                            class="badge badge-light border text-dark font-weight-bold"><?= $row['id_transaksi'] ?></span>
                                    </td>
                                    <td class="font-weight-bold text-dark"><?= $row['kode_divisi'] ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="far fa-file-alt text-muted mr-2"></i> <?= $row['dokumen_utama'] ?>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-light border"><?= $row['rf_id'] ?></span></td>
                                    <td class="text-center font-weight-bold"><?= $row['jml_bantex'] ?></td>
                                    <td class="text-center font-weight-bold"><?= $row['jml_box'] ?></td>
                                    <td class="text-center">
                                        <div class="status-badge <?= $stClass ?>">
                                            <?= $row['status'] ?>
                                            <span class="status-desc"><?= $stDesc ?></span>
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

<?php } ?>