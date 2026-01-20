<?php
// mencegah direct access file PHP
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('location: 404.html');
} else {
    // --- 1. LOGIKA MENANGKAP INPUT FILTER ---
    $nama_bulan = [
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember'
    ];

    // menangkap input agar dropdown tidak reset saat diklik
    if (isset($_POST['filter'])) {
        $bulan_pilih = $_POST['bulan'];
        $tahun_pilih = $_POST['tahun'];
    } else {
        $bulan_pilih = "";
        $tahun_pilih = "";
    }
    ?>
    <div class="panel-header bg-secondary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-top flex-column flex-md-row">
                <div class="page-header text-white">
                    <h4 class="page-title text-white"><i class="fas fa-home mr-2"></i> Dashboard</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="page-inner mt--5">

        <div class="row row-card-no-pd mt--2">

            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big2 text-center">
                                    <i class="flaticon-box-2 text-secondary"></i>
                                </div>
                            </div>
                            <div class="col-7 col-stats">
                                <div class="numbers">
                                    <p class="card-category">Total Pengajuan Box Divisi</p>
                                    <?php
                                    // Query TOTAL MASTER
                                    $query = mysqli_query($mysqli, "SELECT * FROM tbl_barang") or die('Error: ' . mysqli_error($mysqli));
                                    $jumlah_barang = mysqli_num_rows($query);
                                    ?>
                                    <h4 class="card-title"><?php echo number_format($jumlah_barang, 0, '', '.'); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big2 text-center">
                                    <i class="flaticon-inbox text-success"></i>
                                </div>
                            </div>
                            <div class="col-7 col-stats">
                                <div class="numbers">
                                    <p class="card-category">Total Box Diterima Divisi</p>
                                    <?php
                                    // Query TOTAL BARANG MASUK (Tanpa Filter)
                                    $query = mysqli_query($mysqli, "SELECT * FROM tbl_barang_masuk") or die('Error: ' . mysqli_error($mysqli));
                                    $jumlah_barang_masuk = mysqli_num_rows($query);
                                    ?>
                                    <h4 class="card-title"><?php echo number_format($jumlah_barang_masuk, 0, '', '.'); ?>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big2 text-center">
                                    <i class="fas fa-layer-group text-primary"></i>
                                </div>
                            </div>
                            <div class="col-7 col-stats">
                                <div class="numbers">
                                    <p class="card-category">Total Bantex Diterima Divisi</p>
                                    <?php
                                    // Query TOTAL JUMLAH BANTEX (Tanpa Filter)
                                    $query = mysqli_query($mysqli, "SELECT SUM(jumlah) as total_bantex FROM tbl_barang_masuk") or die('Error: ' . mysqli_error($mysqli));
                                    $data = mysqli_fetch_assoc($query);
                                    $total_bantex = $data['total_bantex'];
                                    ?>
                                    <h4 class="card-title"><?php echo number_format($total_bantex, 0, '', '.'); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big2 text-center">
                                    <i class="flaticon-archive text-warning"></i>
                                </div>
                            </div>
                            <div class="col-7 col-stats">
                                <div class="numbers">
                                    <p class="card-category">Total Pengajuan Bantex Divisi</p>
                                    <?php
                                    // Query TOTAL BARANG KELUAR (Tanpa Filter)
                                    $query = mysqli_query($mysqli, "SELECT * FROM tbl_barang_keluar") or die('Error: ' . mysqli_error($mysqli));
                                    $jumlah_barang_keluar = mysqli_num_rows($query);
                                    ?>
                                    <h4 class="card-title"><?php echo number_format($jumlah_barang_keluar, 0, '', '.'); ?>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="mt-2 pb-3">

        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div class="card-title">
                        <i class="fas fa-info-circle mr-2"></i> Riwayat Pengajuan
                    </div>

                    <form action="?module=dashboard" method="post" class="form-inline mt-2 mt-lg-0">
                        <div class="form-group mr-2 mb-0">
                            <select name="bulan" class="form-control form-control-sm">
                                <option value="">- Bulan -</option>
                                <?php foreach ($nama_bulan as $key => $val): ?>
                                    <option value="<?= $key ?>" <?= ($key == $bulan_pilih) ? 'selected' : '' ?>><?= $val ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group mr-2 mb-0">
                            <select name="tahun" class="form-control form-control-sm">
                                <option value="">- Tahun -</option>
                                <?php
                                $tahun_sekarang = date('Y');
                                for ($i = $tahun_sekarang; $i >= $tahun_sekarang - 5; $i--) {
                                    $selected = ($i == $tahun_pilih) ? 'selected' : '';
                                    echo "<option value='$i' $selected>$i</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <button type="submit" name="filter" class="btn btn-primary btn-sm btn-round mr-1"
                            title="Filter Data">
                            <i class="fas fa-filter"></i>
                        </button>
                        <a href="?module=dashboard" class="btn btn-default btn-sm btn-round" title="Reset Filter">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    </form>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="display table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">Box</th>
                                <th class="text-center">Divisi</th>
                                <th class="text-center">RF ID</th>
                                <th class="text-center">Bantex</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            // Query Stok Minimum (Snapshot saat ini, tidak menggunakan filter tanggal)
                            $query = mysqli_query($mysqli, "SELECT a.id_barang, a.nama_barang, a.jenis, a.stok_minimum, a.stok, a.satuan, b.nama_jenis, c.nama_satuan
                                                            FROM tbl_barang as a INNER JOIN tbl_jenis as b INNER JOIN tbl_satuan as c 
                                                            ON a.jenis=b.id_jenis AND a.satuan=c.id_satuan 
                                                            WHERE a.stok<=a.stok_minimum ORDER BY a.id_barang ASC");
                            while ($data = mysqli_fetch_assoc($query)) { ?>
                                <tr>
                                    <td width="50" class="text-center"><?php echo $no++; ?></td>
                                    <td width="80" class="text-center"><?php echo $data['id_barang']; ?></td>
                                    <td width="200"><?php echo $data['nama_barang']; ?></td>
                                    <td width="150"><?php echo $data['nama_jenis']; ?></td>
                                    <td width="70" class="text-right"><span
                                            class="badge badge-warning"><?php echo $data['stok']; ?></span></td>
                                    <td width="70"><?php echo $data['nama_satuan']; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <i class="fas fa-history mr-2"></i> Log Aktivitas Terkini
                        </div>
                    </div>
                    <div class="card-body">
                        <ol class="activity-feed">
                            <?php
                            $dummy_logs = [
                                ['user' => 'Indra Setyawantoro', 'aksi' => 'Menambahkan barang baru <b>Semen Tiga Roda</b>', 'waktu' => '5 menit yang lalu', 'color' => 'success'],
                                ['user' => 'Gudang Staff', 'aksi' => 'Mengeluarkan stok <b>Paku Payung</b> sebanyak 5 Box', 'waktu' => '1 jam yang lalu', 'color' => 'warning'],
                                ['user' => 'Admin', 'aksi' => 'Mengupdate data jenis barang', 'waktu' => 'Hari ini, 09:00', 'color' => 'primary'],
                                ['user' => 'System', 'aksi' => 'Peringatan: Stok <b>Cat Tembok</b> menipis', 'waktu' => 'Kemarin', 'color' => 'danger']
                            ];
                            foreach ($dummy_logs as $log) {
                                ?>
                                <li class="feed-item feed-item-<?php echo $log['color']; ?>">
                                    <time class="date" datetime="9-25"><?php echo $log['waktu']; ?></time>
                                    <span class="text">
                                        <strong><?php echo $log['user']; ?></strong> <br>
                                        <?php echo $log['aksi']; ?>
                                    </span>
                                </li>
                            <?php } ?>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

    </div>
<?php } ?>