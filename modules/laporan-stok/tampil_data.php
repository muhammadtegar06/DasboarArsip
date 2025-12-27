<?php
// Mencegah direct access file PHP
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('location: 404.html');
}
else {
    // Daftar Divisi (Array)
    $daftar_divisi = [
        "DSPN" => "Divisi Sekretariat Perusahaan",
        "DTPI" => "Divisi Satuan Pengawasan Intern",
        "DTAN" => "Divisi Tanaman",
        "DTPL" => "Divisi Teknik & Pengolahan",
        "DINF" => "Divisi Infrastruktur",
        "DITN" => "Divisi Investasi Tanaman",
        "DPSN" => "Divisi Pemasaran",
        "DRPL" => "Divisi Rantai Pasok & Logistik",
        "DPEN" => "Divisi Pengadaan",
        "DSKP" => "Divisi Strategi Perusahaan & Pengendalian Kinerja Anak Perusahaan",
        "DSMS" => "Divisi Sistem Manajemen & Sustainability",
        "DRPH" => "Divisi Riset, Pengembangan Bisnis & Hilirisasi",
        "DKSH" => "Divisi Keuangan Strategis dan Hubungan Investor",
        "DPBA" => "Divisi Perbendaharaan & Anggaran",
        "DAPN" => "Divisi Akuntansi & Perpajakan",
        "DMRS" => "Divisi Manajemen Risiko",
        "DPSB" => "Divisi Pengembangan SDM dan Budaya",
        "DSDM" => "Divisi Operasional SDM",
        "DHPU" => "Divisi HPS & Umum",
        "DTIS" => "Divisi Teknologi Informasi",
        "DHKT" => "Divisi Hubungan Kelembagaan dan TJSL",
        "DHKM" => "Divisi Hukum",
        "DPSR" => "Divisi PSR dan Plasma",
        "DPMO" => "Project Management Office"
    ];
?>
    <div class="panel-header bg-secondary-gradient">
        <div class="page-inner py-4">
            <div class="page-header text-white">
                <h4 class="page-title text-white"><i class="fas fa-file-alt mr-2"></i> Laporan Arsip Per Divisi</h4>
                <ul class="breadcrumbs">
                    <li class="nav-home"><a href="?module=dashboard"><i class="flaticon-home text-white"></i></a></li>
                    <li class="separator"><i class="flaticon-right-arrow"></i></li>
                    <li class="nav-item"><a>Laporan</a></li>
                    <li class="separator"><i class="flaticon-right-arrow"></i></li>
                    <li class="nav-item"><a>Per Divisi</a></li>
                </ul>
            </div>
        </div>
    </div>

    <?php
    // --- KONDISI 1: FORM FILTER BELUM DISUBMIT ---
    if (!isset($_POST['tampil'])) { ?>
        <div class="page-inner mt--5">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Filter Divisi</div>
                </div>
                <div class="card-body">
                    <form action="?module=laporan_stok" method="post" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Pilih Divisi <span class="text-danger">*</span></label>
                                    <select name="divisi" class="form-control select2" required>
                                        <option value="">-- Silahkan Pilih --</option>
                                        <?php foreach($daftar_divisi as $kode => $nama): ?>
                                            <option value="<?= $kode ?>"><?= $kode ?> - <?= $nama ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">Divisi harus dipilih.</div>
                                </div>
                            </div>

                            <div class="col-lg-3">
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
    // --- KONDISI 2: FORM SUDAH DISUBMIT (TAMPIL DATA) ---
    else {
        $divisi_pilih = $_POST['divisi'];
        // Mengambil nama lengkap divisi dari array berdasarkan kode yang dipilih
        $nama_divisi_lengkap = isset($daftar_divisi[$divisi_pilih]) ? $daftar_divisi[$divisi_pilih] : $divisi_pilih;
    ?>
        <div class="page-inner mt--5">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Filter Divisi</div>
                </div>
                <div class="card-body">
                    <form action="?module=laporan_stok" method="post">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Pilih Divisi <span class="text-danger">*</span></label>
                                    <select name="divisi" class="form-control select2" required>
                                        <option value="">-- Silahkan Pilih --</option>
                                        <?php foreach($daftar_divisi as $kode => $nama): ?>
                                            <option value="<?= $kode ?>" <?= ($kode == $divisi_pilih) ? 'selected' : '' ?>>
                                                <?= $kode ?> - <?= $nama ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-group pt-3">
                                    <input type="submit" name="tampil" value="Tampilkan" class="btn btn-secondary btn-round btn-block mt-4">
                                </div>
                            </div>

                            <div class="col-lg-2 pr-0">
                                <div class="form-group pt-3">
                                    <a href="modules/laporan-stok/cetak.php?divisi=<?= $divisi_pilih ?>" target="_blank" class="btn btn-warning btn-round btn-block mt-4">
                                        <span class="btn-label"><i class="fa fa-print mr-2"></i></span> Cetak
                                    </a>
                                </div>
                            </div>

                            <div class="col-lg-2 pl-0">
                                <div class="form-group pt-3">
                                    <a href="modules/laporan-stok/export.php?divisi=<?= $divisi_pilih ?>" target="_blank" class="btn btn-success btn-round btn-block mt-4">
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
                        <i class="fas fa-list-alt mr-2"></i> Data Arsip: <strong><?= $divisi_pilih ?></strong>
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
                                    <th class="text-center">Divisi</th>
                                    <th class="text-center">Total Box</th>
                                    <th class="text-center">Total Bantex</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                // Query ke tabel tbl_barang_masuk berdasarkan DIVISI
                                // Pastikan kolom 'divisi' ada di tbl_barang_masuk
                                $query = mysqli_query($mysqli, "SELECT * FROM tbl_barang_masuk 
                                                                WHERE divisi = '$divisi_pilih' 
                                                                ORDER BY tanggal DESC, id_transaksi DESC")
                                                                or die('Ada kesalahan pada query tampil data : ' . mysqli_error($mysqli));
                                
                                while ($data = mysqli_fetch_assoc($query)) { 
                                    // Handling data kosong jika kolom belum terisi penuh
                                    $t_box = isset($data['total_box']) ? $data['total_box'] : 0;
                                    $t_bantex = isset($data['jumlah']) ? $data['jumlah'] : 0;
                                    $status = isset($data['status']) ? $data['status'] : '-';
                                ?>
                                    <tr>
                                        <td width="50" class="text-center"><?= $no++; ?></td>
                                        <td width="100" class="text-center"><?= $data['id_transaksi']; ?></td>
                                        <td width="100" class="text-center"><?= date('d-m-Y', strtotime($data['tanggal'])); ?></td>
                                        <td width="200"><?= $data['divisi']; ?></td>
                                        <td width="100" class="text-center">
                                            <span class="badge badge-count"><?= $t_box ?> Box</span>
                                        </td>
                                        <td width="100" class="text-center">
                                            <?= number_format($t_bantex, 0, '', '.'); ?>
                                        </td>
                                        <td width="100" class="text-center">
                                            <?php
                                            if ($status == 'ACC') {
                                                echo '<span class="badge badge-success">ACC</span>';
                                            } elseif ($status == 'Reject') {
                                                echo '<span class="badge badge-danger">Reject</span>';
                                            } else {
                                                echo '<span class="badge badge-warning">Waiting</span>';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
?>