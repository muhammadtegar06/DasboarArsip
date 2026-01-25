<?php
// Pastikan path ke config benar
require_once "../../config/database.php";

// Tangkap ID Bantex
$id_bantex = isset($_GET['id_bantex']) ? (int) $_GET['id_bantex'] : 0;

// Query Ambil Dokumen
$q = mysqli_query($mysqli, "SELECT * FROM tbl_dokumen WHERE id_bantex = '$id_bantex' ORDER BY id DESC");
?>

<?php if (mysqli_num_rows($q) > 0): ?>
    <div class="table-responsive mt-3">
        <table class="table table-bordered table-striped table-sm mb-0">
            <thead class="thead-light">
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th>Nama Dokumen</th>
                    <th width="15%" class="text-center">Tahun</th>
                    <th width="20%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($row = mysqli_fetch_assoc($q)):
                    ?>
                    <tr>
                        <td class="text-center align-middle">
                            <?= $no++ ?>
                        </td>
                        <td class="align-middle">
                            <div class="font-weight-bold text-dark">
                                <?= htmlspecialchars($row['nama_dokumen']) ?>
                            </div>
                            <small class="text-muted text-truncate d-block" style="max-width: 250px;">
                                <i class="fas fa-paperclip mr-1"></i>
                                <?= $row['file_dokumen'] ?>
                            </small>
                        </td>
                        <td class="text-center align-middle font-weight-bold">
                            <?= $row['tahun_dokumen'] ?>
                        </td>
                        <td class="text-center align-middle">
                            <a href="uploads/dokumen/<?= $row['file_dokumen'] ?>" target="_blank"
                                class="btn btn-icon btn-xs btn-info shadow-sm" title="Lihat File" data-toggle="tooltip">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button onclick="hapusDokumen(<?= $row['id'] ?>)" class="btn btn-icon btn-xs btn-danger shadow-sm"
                                title="Hapus File" data-toggle="tooltip">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-secondary border-0 text-center mt-3 py-4" style="background-color: #f1f5f9;">
        <i class="fas fa-folder-open fa-3x mb-3 text-muted opacity-50"></i>
        <h6 class="font-weight-bold text-muted">Belum ada dokumen</h6>
        <small class="text-muted">Gunakan form di atas untuk mengupload file ke dalam bantex ini.</small>
    </div>
<?php endif; ?>