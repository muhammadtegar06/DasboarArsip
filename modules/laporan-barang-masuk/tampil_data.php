<?php
// Mencegah direct access
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('location: 404.html');
}
else { 
    // Array Nama Bulan untuk Dropdown dan Judul
    $nama_bulan = [
        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
        '05' => 'Mei',     '06' => 'Juni',     '07' => 'Juli',  '08' => 'Agustus',
        '09' => 'September','10' => 'Oktober',  '11' => 'November','12' => 'Desember'
    ];
?>
    <div class="panel-header bg-secondary-gradient">
        <div class="page-inner py-4">
            <div class="page-header text-white">
                <h4 class="page-title text-white"><i class="fas fa-file-import mr-2"></i> Laporan Barang Masuk</h4>
                <ul class="breadcrumbs">
                    <li class="nav-home"><a href="?module=dashboard"><i class="flaticon-home text-white"></i></a></li>
                    <li class="separator"><i class="flaticon-right-arrow"></i></li>
                    <li class="nav-item"><a>Laporan</a></li>
                    <li class="separator"><i class="flaticon-right-arrow"></i></li>
                    <li class="nav-item"><a>Barang Masuk</a></li>
                </ul>
            </div>
        </div>
    </div>

    <?php
    // --- KONDISI 1: BELUM KLIK TAMPILKAN (FORM KOSONG) ---
    if (!isset($_POST['tampil'])) { ?>
        <div class="page-inner mt--5">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Filter Laporan Periode Bulan</div>
                </div>
                <div class="card-body">
                    <form action="?module=laporan_barang_masuk" method="post">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Bulan <span class="text-danger">*</span></label>
                                    <select name="bulan" class="form-control" required>
                                        <option value="">-- Pilih Bulan --</option>
                                        <?php foreach($nama_bulan as $key => $val): ?>
                                            <option value="<?= $key ?>"><?= $val ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Tahun <span class="text-danger">*</span></label>
                                    <select name="tahun" class="form-control" required>
                                        <option value="">-- Pilih Tahun --</option>
                                        <?php
                                        // Menampilkan tahun dari tahun sekarang mundur 5 tahun ke belakang
                                        $tahun_sekarang = date('Y');
                                        for ($i = $tahun_sekarang; $i >= $tahun_sekarang - 5; $i--) {
                                            echo "<option value='$i'>$i</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-2 pr-0">
                                <div class="form-group pt-3">
                                    <input type="submit" name="tampil" value="Tampilkan" class="btn btn-secondary btn-round btn-block mt-4">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php
    }
    // --- KONDISI 2: SUDAH KLIK TAMPILKAN (HASIL REPORT) ---
    else {
        // Ambil data dari POST
        $bulan_pilih = $_POST['bulan'];
        $tahun_pilih = $_POST['tahun'];
        
        // Label untuk judul laporan
        $label_bulan = $nama_bulan[$bulan_pilih];
    ?>
        <div class="page-inner mt--5">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Filter Laporan Periode Bulan</div>
                </div>
                <div class="card-body">
                    <form action="?module=laporan_barang_masuk" method="post">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Bulan <span class="text-danger">*</span></label>
                                    <select name="bulan" class="form-control" required>
                                        <option value="">-- Pilih Bulan --</option>
                                        <?php foreach($nama_bulan as $key => $val): ?>
                                            <option value="<?= $key ?>" <?= ($key == $bulan_pilih) ? 'selected' : '' ?>>
                                                <?= $val ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Tahun <span class="text-danger">*</span></label>
                                    <select name="tahun" class="form-control" required>
                                        <option value="">-- Pilih Tahun --</option>
                                        <?php
                                        $tahun_sekarang = date('Y');
                                        for ($i = $tahun_sekarang; $i >= $tahun_sekarang - 5; $i--) {
                                            $selected = ($i == $tahun_pilih) ? 'selected' : '';
                                            echo "<option value='$i' $selected>$i</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-2 pr-0">
                                <div class="form-group pt-3">
                                    <input type="submit" name="tampil" value="Tampilkan" class="btn btn-secondary btn-round btn-block mt-4">
                                </div>
                            </div>

                            <div class="col-lg-2 pr-0">
                                <div class="form-group pt-3">
                                    <a href="modules/laporan-barang-masuk/cetak.php?bulan=<?= $bulan_pilih ?>&tahun=<?= $tahun_pilih ?>" target="_blank" class="btn btn-warning btn-round btn-block mt-4">
                                        <span class="btn-label"><i class="fa fa-print mr-2"></i></span> Cetak
                                    </a>
                                </div>
                            </div>

                            <div class="col-lg-2 pl-0">
                                <div class="form-group pt-3">
                                    <a href="modules/laporan-barang-masuk/export.php?bulan=<?= $bulan_pilih ?>&tahun=<?= $tahun_pilih ?>" target="_blank" class="btn btn-success btn-round btn-block mt-4">
                                        <span class="btn-label"><i class="fa fa-file-excel mr-2"></i></span> Export
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <i class="fas fa-file-alt mr-2"></i> Laporan Data Barang Masuk Periode <strong><?= $label_bulan ?> <?= $tahun_pilih ?></strong>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="basic-datatables" class="display table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th class="text-center">ID Transaksi</th>
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-center">Barang</th>
                                    <th class="text-center">Jumlah Masuk</th>
                                    <th class="text-center">Satuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                // Query menggunakan MONTH() dan YEAR()
                                $query = mysqli_query($mysqli, "SELECT a.id_transaksi, a.tanggal, a.barang, a.jumlah, b.nama_barang, c.nama_satuan
                                                                FROM tbl_barang_masuk as a 
                                                                INNER JOIN tbl_barang as b ON a.barang=b.id_barang 
                                                                INNER JOIN tbl_satuan as c ON b.satuan=c.id_satuan 
                                                                WHERE MONTH(a.tanggal) = '$bulan_pilih' 
                                                                AND YEAR(a.tanggal) = '$tahun_pilih' 
                                                                ORDER BY a.tanggal ASC, a.id_transaksi ASC")
                                                                or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
                                
                                while ($data = mysqli_fetch_assoc($query)) { ?>
                                    <tr>
                                        <td width="50" class="text-center"><?= $no++; ?></td>
                                        <td width="90" class="text-center"><?= $data['id_transaksi']; ?></td>
                                        <td width="70" class="text-center"><?= date('d-m-Y', strtotime($data['tanggal'])); ?></td>
                                        <td width="220"><?= $data['barang']; ?> - <?= $data['nama_barang']; ?></td>
                                        <td width="100" class="text-right"><?= number_format($data['jumlah'], 0, '', '.'); ?></td>
                                        <td width="60"><?= $data['nama_satuan']; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    <?php
    }
}
?>