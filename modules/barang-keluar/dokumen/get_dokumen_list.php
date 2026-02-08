<?php
// Sesuaikan path ke database.php
require_once "../../../config/database.php";

$id_bantex = isset($_GET['id_bantex']) ? (int) $_GET['id_bantex'] : 0;

$query = mysqli_query($mysqli, "SELECT * FROM tbl_dokumen WHERE id_bantex = '$id_bantex' ORDER BY id DESC");
?>

<?php if (mysqli_num_rows($query) > 0): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-sm mb-0 bg-white">
            <thead class="thead-light">
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th>Nama Dokumen</th>
                    <th>Nomor Dokumen</th>
                    <th width="10%" class="text-center">Tahun</th>
                    <th class="text-center">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($row = mysqli_fetch_assoc($query)):
                    // Path file untuk view
                    $file_path = "uploads/dokumen/" . $row['file_dokumen'];
                    ?>
                    <tr>
                        <td class="text-center align-middle"><?= $no++ ?></td>
                        <td class="align-middle font-weight-bold"><?= $row['nama_dokumen'] ?></td>
                        <td class="align-middle"><?= $row['nomor_dokumen'] ?></td>
                        <td class="text-center align-middle"><?= $row['tahun_dokumen'] ?></td>
                        <td class="text-center align-middle small text-muted"><?= $row['keterangan'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-secondary text-center mb-0">
        <i class="fas fa-folder-open fa-2x mb-2 text-muted"></i>
        <p class="mb-0 font-weight-bold text-muted">Belum ada dokumen yang diinput.</p>
        <small class="text-muted">Gunakan form di atas untuk menambahkan dokumen baru.</small>
    </div>
<?php endif; ?>