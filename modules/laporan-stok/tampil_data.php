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

    // QUERY RINGAN: Hanya ambil data untuk tabel utama
    $where_clause = "WHERE p.status IN ('Terkirim', 'To Send', 'Telah Dikirim')";
    if ($selected_divisi != '') {
        $where_clause .= " AND d.singkatan_divisi = '$selected_divisi'";
    } else {
        $where_clause .= " AND 1=0";
    }

    $query_laporan = mysqli_query($mysqli, "
        SELECT 
            p.id, p.no_pengajuan as id_transaksi, p.tanggal_pengajuan, p.jumlah_box as jml_box, p.status,
            d.nama_divisi as divisi, d.singkatan_divisi as kode_divisi,
            (SELECT COUNT(*) FROM tbl_bantex b JOIN tbl_box bx ON b.id_box = bx.id WHERE bx.id_pengajuan = p.id) as jml_bantex,
            (SELECT SUM(p2.jumlah_box) FROM tbl_pengajuan p2 JOIN tbl_divisi d2 ON p2.id_divisi = d2.id WHERE d2.singkatan_divisi = '$selected_divisi' AND p2.status IN ('Terkirim', 'To Send', 'Telah Dikirim')) as total_box_header,
            (SELECT COUNT(doc.id) FROM tbl_dokumen doc JOIN tbl_bantex b2 ON doc.id_bantex = b2.id JOIN tbl_box bx2 ON b2.id_box = bx2.id JOIN tbl_pengajuan p3 ON bx2.id_pengajuan = p3.id JOIN tbl_divisi d3 ON p3.id_divisi = d3.id WHERE d3.singkatan_divisi = '$selected_divisi' AND p3.status IN ('Terkirim', 'To Send', 'Telah Dikirim')) as total_dok_header,
            (SELECT h.waktu FROM tbl_history_pengiriman h JOIN tbl_pengiriman pg ON h.id_pengiriman = pg.id WHERE pg.id_pengajuan = p.id AND h.status IN ('Terkirim', 'To Send', 'Telah Dikirim') ORDER BY h.waktu DESC LIMIT 1) as tgl_terkirim
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
        $total_dokumen = $row['total_dok_header'];
        $total_box_fisik = $row['total_box_header'];
    }
    ?>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
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

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            gap: 15px;
        }

        .grid-item {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: 0.2s;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .grid-item:hover {
            border-color: #3b82f6;
            background: #eff6ff;
            transform: translateY(-3px);
        }

        .grid-icon {
            font-size: 32px;
            margin-bottom: 10px;
            display: block;
        }

        .grid-text {
            font-weight: 800;
            font-size: 14px;
            color: #1e293b;
        }

        .t-item {
            position: relative;
            padding-bottom: 25px;
            padding-left: 35px;
            border-left: 2px solid #e2e8f0;
        }

        .t-item:last-child {
            border-left-color: transparent;
        }

        .t-icon {
            position: absolute;
            left: -16px;
            top: 0;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            border: 3px solid white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .select2-container .select2-selection--single {
            height: 35px !important;
            border: 1px solid #ebedf2 !important;
            border-radius: 5px !important;
        }
    </style>

    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-4">
            <h2 class="text-white pb-2 fw-bold"><i class="fas fa-file-contract mr-2"></i> Laporan Arsip Terkirim</h2>
            <h5 class="text-white op-7 mb-2">Rekapitulasi seluruh dokumen fisik per divisi yang telah berstatus Terkirim.
            </h5>
        </div>
    </div>

    <div class="page-inner mt--5">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-header bg-white pt-4 pb-3">
                <div class="row align-items-center">
                    <div class="col-md-5">
                        <?php if ($selected_divisi != ''): ?>
                            <div class="d-flex">
                                <div class="stat-box"><i class="fas fa-file-alt"></i>
                                    <div><small class="text-muted d-block">Total Dokumen</small>
                                        <?= number_format($total_dokumen) ?> File</div>
                                </div>
                                <div class="stat-box"><i class="fas fa-box text-warning"></i>
                                    <div><small class="text-muted d-block">Total Box</small>
                                        <?= number_format($total_box_fisik) ?> Box</div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-7">
                        <form action="" method="GET" class="d-flex align-items-center justify-content-md-end">
                            <input type="hidden" name="module" value="<?= $_GET['module'] ?>">
                            <label class="font-weight-bold mr-3 mb-0">Filter Divisi :</label>
                            <div style="min-width: 250px;">
                                <select name="filter_divisi" class="form-control select2-single"
                                    onchange="this.form.submit()">
                                    <option value=""></option>
                                    <?php foreach ($divisi_options as $d): ?>
                                        <option value="<?= $d['singkatan_divisi'] ?>"
                                            <?= ($selected_divisi == $d['singkatan_divisi']) ? 'selected' : ''; ?>>
                                            <?= $d['singkatan_divisi'] . ' - ' . $d['nama_divisi'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-custom mb-0" id="laporan-datatables">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center pl-4">No</th>
                                <th width="18%">ID Transaksi</th>
                                <th width="20%">Divisi</th>
                                <th width="22%">Tgl Update Status</th>
                                <th width="15%" class="text-center">Isi Box</th>
                                <th width="10%" class="text-center">Status</th>
                                <th width="10%" class="text-center pr-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($selected_divisi == '') {
                                echo '<tr><td colspan="7" class="text-center py-5 text-muted">Silakan pilih divisi terlebih dahulu.</td></tr>';
                            } else if (empty($data_tampil)) {
                                echo '<tr><td colspan="7" class="text-center py-5 text-muted font-italic">Belum ada riwayat dokumen Terkirim.</td></tr>';
                            } else {
                                $no = 1;
                                foreach ($data_tampil as $row) {
                                    $tgl = !empty($row['tgl_terkirim']) ? date('d M Y, H:i', strtotime($row['tgl_terkirim'])) : 'Belum Tercatat';
                                    ?>
                                        <tr>
                                            <td class="text-center text-muted pl-4"><?= $no++; ?></td>
                                            <td><span class="font-weight-bold text-primary"><?= $row['id_transaksi'] ?></span></td>
                                            <td><span class="font-weight-bold"><?= $row['kode_divisi'] ?></span><br><small
                                                    class="text-muted">Aju:
                                                <?= date('d M Y', strtotime($row['tanggal_pengajuan'])) ?></small></td>
                                            <td><i class="far fa-clock text-warning mr-1"></i> <span
                                                    class="font-weight-bold text-dark"><?= $tgl ?></span></td>
                                            <td class="text-center">
                                                <div class="badge badge-light border text-dark"><?= $row['jml_box'] ?> Box |
                                                <?= $row['jml_bantex'] ?> Btx</div>
                                            </td>
                                            <td class="text-center"><span class="badge badge-success px-3 py-1 shadow-sm"
                                                    style="border-radius: 20px;"><i class="fas fa-check-circle mr-1"></i>
                                                    TERKIRIM</span></td>
                                            <td class="text-center pr-4">
                                                <button type="button" onclick="openModalDetail(<?= $row['id'] ?>)"
                                                    class="btn btn-sm btn-info btn-round shadow-sm px-3">
                                                    <i class="fas fa-eye mr-1"></i> Detail
                                                </button>
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

    <div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content border-0" style="border-radius: 15px; overflow: hidden;">
                <div class="modal-card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge badge-warning text-white mb-2 px-3 py-1" id="m_id"
                                style="border-radius: 20px;">TRX-000</span>
                            <h5 class="font-weight-bold text-white mb-0" id="m_divisi">Nama Divisi</h5>
                        </div>
                        <button type="button" class="close text-white opacity-1" data-dismiss="modal">&times;</button>
                    </div>
                </div>
                <div class="nav-tabs-custom">
                    <div class="nav-item-custom active" onclick="switchTab('lacak')"><i
                            class="fas fa-map-marker-alt mr-2"></i> Lacak Status</div>
                    <div class="nav-item-custom" onclick="switchTab('isi')"><i class="fas fa-folder-open mr-2"></i> Explorer
                        Fisik</div>
                </div>
                <div class="modal-body bg-light" style="min-height: 400px;" id="modalContentLoader">
                    <div id="modalLoading" class="text-center py-5"><i
                            class="fas fa-spinner fa-spin fa-3x text-primary"></i>
                        <p class="mt-3">Memuat Data...</p>
                    </div>

                    <div id="modalDataArea" style="display:none;">
                        <div id="tab-lacak" class="tab-pane active">
                            <div class="row justify-content-center mb-4">
                                <div class="col-6">
                                    <div class="card card-stats card-round mb-0 border shadow-sm">
                                        <div class="card-body p-3 text-center"><small
                                                class="text-muted font-weight-bold">TOTAL BOX</small>
                                            <h3 class="font-weight-bold text-primary mb-0 mt-1" id="m_jmlBox">0</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card card-stats card-round mb-0 border shadow-sm">
                                        <div class="card-body p-3 text-center"><small
                                                class="text-muted font-weight-bold">TOTAL BANTEX</small>
                                            <h3 class="font-weight-bold text-info mb-0 mt-1" id="m_jmlBantex">0</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white p-4 rounded border shadow-sm">
                                <h6 class="font-weight-bold mb-4 text-dark border-bottom pb-2"><i
                                        class="fas fa-history mr-2 text-primary"></i> Riwayat Pergerakan Dokumen</h6>
                                <div id="timelineContainer"></div>
                            </div>
                        </div>
                        <div id="tab-isi" class="tab-pane">
                            <div id="view-box">
                                <div class="text-center mb-3">
                                    <h6 class="font-weight-bold text-muted"><i class="fas fa-boxes mr-2"></i>Pilih Box untuk
                                        melihat detail isi bantex</h6>
                                </div>
                                <div class="grid-container" id="gridBox"></div>
                            </div>
                            <div id="view-bantex" style="display:none;">
                                <div class="d-flex align-items-center mb-4 pb-2 border-bottom"><button
                                        class="btn btn-sm btn-icon btn-round btn-light border mr-3"
                                        onclick="showView('view-box')"><i class="fas fa-arrow-left"></i></button>
                                    <h6 class="font-weight-bold text-dark mb-0" id="bantex-box-title">Isi Box</h6>
                                </div>
                                <div class="grid-container" id="gridBantex"></div>
                            </div>
                            <div id="view-doc" style="display:none;">
                                <div class="d-flex align-items-center mb-4 pb-2 border-bottom"><button
                                        class="btn btn-sm btn-icon btn-round btn-light border mr-3"
                                        onclick="showView('view-bantex')"><i class="fas fa-arrow-left"></i></button>
                                    <h6 class="font-weight-bold text-dark mb-0" id="doc-bantex-title">Daftar Dokumen</h6>
                                </div>
                                <div class="bg-white rounded border shadow-sm overflow-hidden">
                                    <table class="table table-hover mb-0">
                                        <tbody id="tableDoc"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.select2-single').select2({ placeholder: "-- Pilih Divisi --", allowClear: true });
            $('#laporan-datatables').DataTable({ "pageLength": 10, "ordering": false, "language": { "search": "Cari Cepat:" } });
        });

        let currentData = null;

        function openModalDetail(id) {
            $('#modalDataArea').hide();
            $('#modalLoading').show();
            $('#modalDetail').modal('show');

            // AJAX AMBIL DATA HANYA SAAT DIBUTUHKAN
            $.get('modules/laporan-stok/get_detail_laporan.php?id=' + id, function (resp) {
                currentData = JSON.parse(resp);

                $('#m_id').text(currentData.id_transaksi);
                $('#m_divisi').text(currentData.divisi);
                $('#m_jmlBox').text(currentData.jml_box);
                $('#m_jmlBantex').text(currentData.jml_bantex);

                let htmlTimeline = '';
                currentData.history.forEach(h => {
                    let visualStatus = (h.status === 'TO SEND' || h.status === 'TELAH DIKIRIM') ? 'TERKIRIM' : h.status;
                    htmlTimeline += `<div class="t-item"><div class="t-icon bg-${h.color}"><i class="fas fa-check"></i></div><div><div class="font-weight-bold text-dark">${visualStatus}</div><small class="text-muted"><i class="far fa-clock mr-1"></i> ${h.date}</small><div class="small text-${h.color} font-weight-bold mt-1"><i class="fas fa-user mr-1"></i> ${h.user}</div></div></div>`;
                });
                $('#timelineContainer').html(htmlTimeline);

                generateBox(currentData.boxes);
                switchTab('lacak');

                $('#modalLoading').hide();
                $('#modalDataArea').show();
            });
        }

        function switchTab(tab) {
            $('.nav-item-custom').removeClass('active');
            $('.tab-pane').removeClass('active');
            if (tab === 'lacak') { $('.nav-item-custom').eq(0).addClass('active'); $('#tab-lacak').addClass('active'); }
            else { $('.nav-item-custom').eq(1).addClass('active'); $('#tab-isi').addClass('active'); showView('view-box'); }
        }

        function showView(viewId) { $('#view-box, #view-bantex, #view-doc').hide(); $('#' + viewId).fadeIn(300); }

        function generateBox(boxes) {
            let html = '';
            boxes.forEach((bx, index) => {
                html += `<div class="grid-item" onclick="openBantex(${index})"><span class="grid-icon text-warning"><i class="fas fa-box"></i></span><div class="grid-text">BOX ${index + 1}</div><div class="small text-muted mt-2">Buka Isi Box</div></div>`;
            });
            $('#gridBox').html(html || '<div class="col-12 text-center text-muted py-4">Data Box tidak ditemukan.</div>');
        }

        function openBantex(boxIndex) {
            let box = currentData.boxes[boxIndex];
            let html = '';
            box.bantex.forEach((bt, index) => {
                html += `<div class="grid-item" onclick="openDoc(${boxIndex}, ${index})"><span class="grid-icon text-info"><i class="fas fa-folder-open"></i></span><div class="grid-text">${bt.nama_bantex}</div><div class="small text-muted mt-1 text-truncate">${bt.label_judul || 'Belum dilabeli'}</div></div>`;
            });
            $('#gridBantex').html(html || '<div class="col-12 text-center text-muted py-4">Box Kosong.</div>');
            $('#bantex-box-title').text('Daftar Bantex pada BOX ' + (boxIndex + 1));
            showView('view-bantex');
        }

        function openDoc(boxIndex, bantexIndex) {
            let bantex = currentData.boxes[boxIndex].bantex[bantexIndex];
            let html = '';
            if (bantex.dokumen.length > 0) {
                bantex.dokumen.forEach(doc => {
                    let btn = doc.file_dokumen ? `<a href="uploads/dokumen/${doc.file_dokumen}" target="_blank" class="btn btn-sm btn-primary btn-round">Buka</a>` : `<span class="badge badge-light border">Fisik</span>`;
                    html += `<tr><td class="pl-4 py-3"><div class="d-flex align-items-center"><div class="bg-light p-2 rounded mr-3 border"><i class="fas fa-file-alt text-danger"></i></div><div><div class="font-weight-bold text-dark">${doc.nama_dokumen}</div><small class="text-muted">${doc.nomor_dokumen} | ${doc.tahun_dokumen}</small></div></div></td><td class="text-right pr-4">${btn}</td></tr>`;
                });
            } else { html = '<tr><td colspan="2" class="text-center text-muted py-5">Belum ada dokumen digital.</td></tr>'; }
            $('#tableDoc').html(html);
            $('#doc-bantex-title').text('Isi Dokumen: ' + bantex.nama_bantex);
            showView('view-doc');
        }
    </script>
<?php } ?>