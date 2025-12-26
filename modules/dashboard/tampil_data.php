<?php
// mencegah direct access file PHP agar file PHP tidak bisa diakses secara langsung dari browser dan hanya dapat dijalankan ketika di include oleh file lain
// jika file diakses secara langsung
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    // alihkan ke halaman error 404
    header('location: 404.html');
}
// jika file di include oleh file lain, tampilkan isi file
else {
    // menampilkan pesan selamat datang
    // jika pesan tersedia
    if (isset($_GET['pesan'])) {
        // jika pesan = 1
        if ($_GET['pesan'] == 1) {
            // tampilkan pesan selamat datang
            echo '  <div class="alert alert-notify alert-secondary alert-dismissible fade show" role="alert">
                        <span data-notify="icon" class="fas fa-user-alt"></span> 
                        <span data-notify="title" class="text-secondary">Hi! ' . $_SESSION['nama_user'] . '</span> 
                        <span data-notify="message">Selamat Datang di Sistem Informasi Persediaan Barang Gudang Material.</span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
        }
    }
?>

<style>
/* Style untuk Log Aktivitas Timeline */
.activity-feed {
    padding: 15px;
    list-style: none;
}
.feed-item {
    position: relative;
    padding-bottom: 20px;
    padding-left: 30px;
    border-left: 2px solid #e4e8eb;
}
.feed-item:last-child {
    border-color: transparent;
}
/* Titik (Dot) Timeline */
.feed-item::after {
    content: "";
    display: block;
    position: absolute;
    top: 0;
    left: -6px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #fff;
    border: 2px solid;
}
/* Warna Dot berdasarkan status */
.feed-item-success::after { border-color: #31ce36; background: #31ce36; }
.feed-item-warning::after { border-color: #ffad46; background: #ffad46; }
.feed-item-danger::after  { border-color: #f25961; background: #f25961; }
.feed-item-primary::after { border-color: #1572e8; background: #1572e8; }

.feed-item .date {
    display: block;
    position: relative;
    top: -5px;
    color: #8d9498;
    text-transform: uppercase;
    font-size: 11px;
    letter-spacing: 0.5px;
}
.feed-item .text {
    position: relative;
    top: -3px;
    font-size: 13px;
}
</style>

    <div class="panel-header bg-secondary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-top flex-column flex-md-row">
                <div class="page-header text-white">
                    <!-- judul halaman -->
                    <h4 class="page-title text-white"><i class="fas fa-home mr-2"></i> Dashboard</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row row-card-no-pd mt--2">
            <!-- menampilkan informasi jumlah data barang -->
            <div class="col-sm-12 col-md-4">
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
                                    <p class="card-category">Total Pengajuan Box</p>
                                    <?php
                                    // sql statement untuk menampilkan jumlah data pada tabel "tbl_barang"
                                    $query = mysqli_query($mysqli, "SELECT * FROM tbl_barang")
                                                                    or die('Ada kesalahan pada query jumlah data barang : ' . mysqli_error($mysqli));
                                    // ambil jumlah data dari hasil query
                                    $jumlah_barang = mysqli_num_rows($query);
                                    ?>
                                    <!-- tampilkan data -->
                                    <h4 class="card-title"><?php echo number_format($jumlah_barang, 0, '', '.'); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- menampilkan informasi jumlah data barang masuk -->
            <div class="col-sm-12 col-md-4">
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
                                    <p class="card-category">Total Box yang Di Setujui</p>
                                    <?php
                                    // sql statement untuk menampilkan jumlah data pada tabel "tbl_barang_masuk"
                                    $query = mysqli_query($mysqli, "SELECT * FROM tbl_barang_masuk")
                                                                    or die('Ada kesalahan pada query jumlah data barang masuk : ' . mysqli_error($mysqli));
                                    // ambil jumlah data dari hasil query
                                    $jumlah_barang_masuk = mysqli_num_rows($query);
                                    ?>
                                    <!-- tampilkan data -->
                                    <h4 class="card-title"><?php echo number_format($jumlah_barang_masuk, 0, '', '.'); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- menampilkan informasi jumlah data barang keluar -->
            <div class="col-sm-12 col-md-4">
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
                                    <p class="card-category">Total Box Belum di Setujui<p>
                                    <?php
                                    // sql statement untuk menampilkan jumlah data pada tabel "tbl_barang_keluar"
                                    $query = mysqli_query($mysqli, "SELECT * FROM tbl_barang_keluar")
                                                                    or die('Ada kesalahan pada query jumlah data barang keluar : ' . mysqli_error($mysqli));
                                    // ambil jumlah data dari hasil query
                                    $jumlah_barang_keluar = mysqli_num_rows($query);
                                    ?>
                                    <!-- tampilkan data -->
                                    <h4 class="card-title"><?php echo number_format($jumlah_barang_keluar, 0, '', '.'); ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        // mengecek hak akses
        // jika hak akses bukan "Kepala Gudang" 
        if ($_SESSION['hak_akses'] != 'Kepala Gudang') { ?>
            <!-- tampilkan informasi jumlah data jenis barang, satuan, dan user -->
            <div class="row">
                <!-- menampilkan informasi jumlah data jenis barang -->
                <div class="col-sm-12 col-md-4">
                    <div class="card card-stats card-round">
                        <div class="card-body ">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-warning bubble-shadow-small">
                                        <i class="fas fa-clone"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ml-3 ml-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Box Tersedia</p>
                                        <?php
                                        // sql statement untuk menampilkan jumlah data pada tabel "tbl_jenis"
                                        $query = mysqli_query($mysqli, "SELECT * FROM tbl_jenis")
                                                                        or die('Ada kesalahan pada query jumlah data jenis barang : ' . mysqli_error($mysqli));
                                        // ambil jumlah data dari hasil query
                                        $jumlah_jenis_barang = mysqli_num_rows($query);
                                        ?>
                                        <!-- tampilkan data -->
                                        <h4 class="card-title"><?php echo number_format($jumlah_jenis_barang, 0, '', '.'); ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- menampilkan informasi jumlah data satuan -->
                <div class="col-sm-12 col-md-4">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-info bubble-shadow-small">
                                        <i class="fas fa-folder-open"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ml-3 ml-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Box Terpakai</p>
                                        <?php
                                        // sql statement untuk menampilkan jumlah data pada tabel "tbl_satuan"
                                        $query = mysqli_query($mysqli, "SELECT * FROM tbl_satuan")
                                                                        or die('Ada kesalahan pada query jumlah data satuan : ' . mysqli_error($mysqli));
                                        // ambil jumlah data dari hasil query
                                        $jumlah_satuan = mysqli_num_rows($query);
                                        ?>
                                        <!-- tampilkan data -->
                                        <h4 class="card-title"><?php echo number_format($jumlah_satuan, 0, '', '.'); ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- menampilkan informasi jumlah data user -->
                <div class="col-sm-12 col-md-4">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-success bubble-shadow-small">
                                        <i class="fas fa-user-circle"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ml-3 ml-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Box Tersisa</p>
                                        <?php
                                        // sql statement untuk menampilkan jumlah data pada tabel "tbl_user"
                                        $query = mysqli_query($mysqli, "SELECT * FROM tbl_user")
                                                                        or die('Ada kesalahan pada query jumlah data user : ' . mysqli_error($mysqli));
                                        // ambil jumlah data dari hasil query
                                        $jumlah_user = mysqli_num_rows($query);
                                        ?>
                                        <!-- tampilkan data -->
                                        <h4 class="card-title"><?php echo number_format($jumlah_user, 0, '', '.'); ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="mt-2 pb-3">
        <?php } ?>

        <!-- menampilkan informasi stok barang yang telah mencapai batas minimum -->
        <div class="card">
            <div class="card-header">
                <!-- judul tabel -->
                <div class="card-title"><i class="fas fa-info-circle mr-2"></i> Stok barang telah mencapai batas minimum</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <!-- tabel untuk menampilkan data dari database -->
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
                            // variabel untuk nomor urut tabel
                            $no = 1;
                            // sql statement untuk menampilkan data dari tabel "tbl_barang", tabel "tbl_jenis", dan tabel "tbl_satuan" berdasarkan "stok"
                            $query = mysqli_query($mysqli, "SELECT a.id_barang, a.nama_barang, a.jenis, a.stok_minimum, a.stok, a.satuan, b.nama_jenis, c.nama_satuan
                                                            FROM tbl_barang as a INNER JOIN tbl_jenis as b INNER JOIN tbl_satuan as c 
                                                            ON a.jenis=b.id_jenis AND a.satuan=c.id_satuan 
                                                            WHERE a.stok<=a.stok_minimum ORDER BY a.id_barang ASC")
                                                            or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
                            // ambil data hasil query
                            while ($data = mysqli_fetch_assoc($query)) { ?>
                                <!-- tampilkan data -->
                                <tr>
                                    <td width="50" class="text-center"><?php echo $no++; ?></td>
                                    <td width="80" class="text-center"><?php echo $data['id_barang']; ?></td>
                                    <td width="200"><?php echo $data['nama_barang']; ?></td>
                                    <td width="150"><?php echo $data['nama_jenis']; ?></td>
                                    <td width="70" class="text-right"><span class="badge badge-warning"><?php echo $data['stok']; ?></span></td>
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
                    // CONTOH QUERY: Pastikan kamu sudah punya tabel log (misal: tbl_log)
                    // Struktur tabel asumsi: id_log, id_user, aksi, tanggal
                    
                    // $query_log = mysqli_query($mysqli, "SELECT a.*, b.nama_user FROM tbl_log a JOIN tbl_user b ON a.id_user = b.id_user ORDER BY a.tanggal DESC LIMIT 5");
                    // while ($log = mysqli_fetch_assoc($query_log)) { 
                    
                    // --- DATA DUMMY (Hapus bagian ini jika backend sudah siap) ---
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
                    <?php } 
                    // --- END DUMMY ---
                    // } // End while loop database
                    ?>
                </ol>
            </div>
        </div>
    </div>
</div>
    </div>
<?php } ?>