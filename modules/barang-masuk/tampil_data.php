<?php
// mencegah direct access file PHP agar file PHP tidak bisa diakses secara langsung dari browser dan hanya dapat dijalankan ketika di include oleh file lain
// jika file diakses secara langsung
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    // alihkan ke halaman error 404
    header('location: 404.html');
}
// jika file di include oleh file lain, tampilkan isi file
else {
    // menampilkan pesan sesuai dengan proses yang dijalankan
    // jika pesan tersedia
    if (isset($_GET['pesan'])) {
        // jika pesan = 1
        if ($_GET['pesan'] == 1) {
            // tampilkan pesan sukses simpan data
            echo '  <div class="alert alert-notify alert-success alert-dismissible fade show" role="alert">
                        <span data-notify="icon" class="fas fa-check"></span> 
                        <span data-notify="title" class="text-success">Sukses!</span> 
                        <span data-notify="message">Data barang masuk berhasil disimpan.</span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
        }
        // jika pesan = 2
        elseif ($_GET['pesan'] == 2) {
            // tampilkan pesan sukses hapus data
            echo '  <div class="alert alert-notify alert-success alert-dismissible fade show" role="alert">
                        <span data-notify="icon" class="fas fa-check"></span> 
                        <span data-notify="title" class="text-success">Sukses!</span> 
                        <span data-notify="message">Data barang masuk berhasil dihapus.</span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>';
        }
    }
?>
    <div class="panel-header bg-secondary-gradient">
        <div class="page-inner py-45">
            <div class="d-flex align-items-left align-items-md-top flex-column flex-md-row">
                <div class="page-header text-white">
                    <!-- judul halaman -->
                    <h4 class="page-title text-white"><i class="fas fa-sign-in-alt mr-2"></i> Data Box</h4>
                    <!-- breadcrumbs -->
                    <ul class="breadcrumbs">
                        <li class="nav-home"><a href="?module=dashboard"><i class="flaticon-home text-white"></i></a></li>
                        <li class="separator"><i class="flaticon-right-arrow"></i></li>
                        <li class="nav-item"><a href="?module=barang_masuk" class="text-white">Data Box</a></li>
                        <li class="separator"><i class="flaticon-right-arrow"></i></li>
                        <li class="nav-item"><a>Data</a></li>
                    </ul>
                </div>
                <div class="ml-md-auto py-2 py-md-0">
                    <!-- button entri data -->
                    <a href="?module=form_entri_barang_masuk" class="btn btn-secondary btn-round">
                        <span class="btn-label"><i class="fa fa-plus mr-2"></i></span> Entri Data
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-inner mt--5">
        <div class="card">
            <div class="card-header">
                <!-- judul tabel -->
                <div class="card-title">Data Box Divisi</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <!-- tabel untuk menampilkan data dari database -->
                    <table id="basic-datatables" class="display table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">No.</th>
                                <th class="text-center">Divisi</th>
                                <th class="text-center">Tanggal Pengajuan</th>
                                <th class="text-center">Box</th>
                                <th class="text-center">RF ID</th>
                                <th class="text-center">Bantex</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // variabel untuk nomor urut tabel
                            $no = 1;
                            // sql statement untuk menampilkan data dari tabel "tbl_barang_masuk", tabel "tbl_barang", dan tabel "tbl_satuan"
                            $query = mysqli_query($mysqli, "SELECT a.id_transaksi, a.tanggal, a.barang, a.jumlah, b.nama_barang, c.nama_satuan
                                                            FROM tbl_barang_masuk as a INNER JOIN tbl_barang as b INNER JOIN tbl_satuan as c
                                                            ON a.barang=b.id_barang AND b.satuan=c.id_satuan 
                                                            ORDER BY a.id_transaksi DESC")
                                                            or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
                            // ambil data hasil query
                            while ($data = mysqli_fetch_assoc($query)) {
                                // dapatkan status, fallback ke 'waiting' jika tidak ada
                                $status_text = isset($data['status']) ? strtolower(trim($data['status'])) : 'waiting';
                                if ($status_text === 'accept' || $status_text === 'accepted') {
                                    $status_badge = '<span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> Accept</span>';
                                } elseif ($status_text === 'reject' || $status_text === 'rejected') {
                                    $status_badge = '<span class="badge badge-danger"><i class="fas fa-times-circle mr-1"></i> Reject</span>';
                                } else {
                                    $status_badge = '<span class="badge badge-warning"><i class="fas fa-clock mr-1"></i> Waiting</span>';
                                }
                                ?>
                                <tr>
                                    <td width="50" class="text-center"><?php echo $no++; ?></td>
                                    <td width="90" class="text-center"><?php echo htmlspecialchars($data['id_transaksi']); ?></td>
                                    <td width="70" class="text-center"><?php echo date('d-m-Y', strtotime($data['tanggal'])); ?></td>
                                    <td width="220"><?php echo htmlspecialchars($data['barang'] . ' - ' . $data['nama_barang']); ?></td>
                                    <td width="100" class="text-right"><?php echo number_format($data['jumlah'], 0, '', '.'); ?></td>
                                    <td width="60"><?php echo htmlspecialchars($data['nama_satuan']); ?></td>
                                    <td width="120" class="text-center"><?php echo $status_badge; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
