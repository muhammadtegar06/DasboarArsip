<?php
// Mencegah direct access
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('location: 404.html');
}
else {
    // 1. Ambil ID dari URL
    if (isset($_GET['id'])) {
        $id_transaksi = $_GET['id'];

        // 2. Query Data Lama untuk ditampilkan
        $query = mysqli_query($mysqli, "SELECT * FROM tbl_barang_masuk WHERE id_transaksi='$id_transaksi'") 
                 or die('Query Error : ' . mysqli_error($mysqli));
        $data = mysqli_fetch_assoc($query);

        // Pecah data ke variabel biar mudah
        $divisi_lama  = $data['divisi'];
        $tanggal_lama = $data['tanggal'];
        $bantex_lama  = $data['jumlah']; // Asumsi kolom 'jumlah' adalah total bantex
        $box_lama     = $data['total_box'];
    }
?>
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-4">
            <div class="page-header text-white">
                <h4 class="page-title text-white"><i class="fas fa-pen mr-2"></i> Ubah Data Arsip</h4>
                <ul class="breadcrumbs">
                    <li class="nav-home"><a href="?module=dashboard"><i class="flaticon-home text-white"></i></a></li>
                    <li class="separator"><i class="flaticon-right-arrow"></i></li>
                    <li class="nav-item"><a href="?module=barang_masuk" class="text-white">Data Box</a></li>
                    <li class="separator"><i class="flaticon-right-arrow"></i></li>
                    <li class="nav-item"><a>Ubah</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="page-inner mt--5">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Form Perubahan Data</div>
            </div>
            
            <form action="modules/barang-masuk/proses_ubah.php" method="post">
                <div class="card-body">
                    <div class="form-group">
                        <label>ID Transaksi</label>
                        <input type="text" name="id_transaksi" class="form-control" value="<?php echo $id_transaksi; ?>" readonly style="background-color: #eee;">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Divisi <span class="text-danger">*</span></label>
                                <input type="text" name="divisi" class="form-control" value="<?php echo $divisi_lama; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tanggal Pengajuan <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal" class="form-control" value="<?php echo $tanggal_lama; ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jumlah Bantex <span class="text-danger">*</span></label>
                                <input type="number" id="jumlah_bantex" name="jumlah_bantex" class="form-control" value="<?php echo $bantex_lama; ?>" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Total Box (Otomatis) <span class="text-danger">*</span></label>
                                <input type="number" id="total_box" name="total_box" class="form-control" value="<?php echo $box_lama; ?>" readonly style="background-color: #f5f5f5;">
                                <small class="text-muted">Dihitung otomatis: 1 Box = 6 Bantex</small>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-action">
                    <button type="submit" class="btn btn-success btn-round pl-4 pr-4 mr-2">
                        <i class="fas fa-save mr-1"></i> Simpan Perubahan
                    </button>
                    
                    <a href="?module=barang_masuk" class="btn btn-default btn-round pl-4 pr-4">
                        <i class="fas fa-undo mr-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Script Sederhana Hitung Box Otomatis saat Edit
        document.getElementById('jumlah_bantex').addEventListener('input', function() {
            let bantex = parseInt(this.value);
            if(bantex > 0) {
                let box = Math.ceil(bantex / 6);
                document.getElementById('total_box').value = box;
            } else {
                document.getElementById('total_box').value = 0;
            }
        });
    </script>
<?php } ?>