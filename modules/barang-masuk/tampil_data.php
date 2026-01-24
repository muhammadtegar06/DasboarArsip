<?php
// Mencegah akses langsung
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('location: 404.html');
} else {
    ?>
    <div class="panel-header bg-secondary-gradient">
        <div class="page-inner py-45">
            <div class="d-flex align-items-left align-items-md-top flex-column flex-md-row">
                <div class="page-header text-white">
                    <h4 class="page-title text-white"><i class="fas fa-check-double mr-2"></i> Approval Arsip Masuk</h4>
                </div>
                <div class="ml-md-auto py-2 py-md-0">
                    <a href="?module=form_entri_barang_masuk" class="btn btn-white btn-border btn-round mr-2">
                        <i class="fa fa-plus mr-2"></i> Buat Pengajuan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-inner mt--5">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom-0 pt-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title font-weight-bold text-dark">Daftar Pengajuan Box</h4>
                </div>
            </div>
            <div class="card-body px-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center" width="5%">#</th>
                                <th width="20%">ID Transaksi</th>
                                <th>Divisi & Tanggal</th>
                                <th class="text-center">Volume Arsip</th>
                                <th width="15%">Status Barang</th>
                                <th class="text-center" width="15%">Aksi Approval</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // --- QUERY DATABASE UPDATE ---
                            // Sekarang kita ambil langsung kolom 'jumlah_box' dari tabel pengajuan
                            $query = mysqli_query($mysqli, "
                                SELECT 
                                    p.id AS id_pengajuan,
                                    p.no_pengajuan,
                                    p.tanggal_pengajuan,
                                    p.jumlah_box,        -- Kolom BARU yang ditambahkan
                                    p.status,
                                    d.nama_divisi,
                                    d.singkatan_divisi
                                FROM tbl_pengajuan p
                                INNER JOIN tbl_divisi d ON p.id_divisi = d.id
                                ORDER BY p.id DESC
                            ");

                            if (mysqli_num_rows($query) == 0) {
                                echo '<tr><td colspan="6" class="text-center py-5 text-muted">Belum ada pengajuan masuk.</td></tr>';
                            } else {
                                $no = 1;
                                while ($data = mysqli_fetch_assoc($query)) {
                                    $id = $data['id_pengajuan'];
                                    $id_trx = $data['no_pengajuan'];

                                    // Format Divisi & Tanggal
                                    $divisi = $data['singkatan_divisi'] . " - " . $data['nama_divisi'];
                                    $tanggal = date('d M Y', strtotime($data['tanggal_pengajuan']));

                                    // LOGIKA VOLUME (Rule: 1 Box = 6 Bantex)
                                    // Mengambil langsung dari kolom database baru
                                    $box = $data['jumlah_box'];
                                    $bantex = $box * 6;

                                    $status = $data['status'];
                                    ?>
                                    <tr>
                                        <td class="text-center text-muted"><?= $no++; ?></td>

                                        <td>
                                            <span class="badge badge-light border text-dark font-weight-bold"
                                                style="font-family: monospace; font-size: 13px; letter-spacing: 0.5px;">
                                                <?= $id_trx ?>
                                            </span>
                                        </td>

                                        <td>
                                            <div class="font-weight-bold text-dark"><?= $divisi ?></div>
                                            <div class="small text-muted"><i class="far fa-calendar-alt mr-1"></i> <?= $tanggal ?>
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <h5 class="mb-0 font-weight-bold text-dark"><?= $box ?> Box</h5>
                                            <small class="text-muted text-primary font-weight-bold">(Estimasi <?= $bantex ?>
                                                Bantex)</small>
                                        </td>

                                        <td>
                                            <?php if ($status == 'Pending') { ?>
                                                <span class="badge badge-warning text-white shadow-sm" style="font-size: 11px;">
                                                    <i class="fas fa-truck-loading mr-1"></i> Menunggu Approval
                                                </span>
                                            <?php } elseif ($status == 'Disetujui' || $status == 'Diterima') { ?>
                                                <span class="badge badge-success shadow-sm" style="font-size: 11px;">
                                                    <i class="fas fa-box-open mr-1"></i> Disetujui
                                                </span>
                                            <?php } else { ?>
                                                <span class="badge badge-danger shadow-sm" style="font-size: 11px;">
                                                    <i class="fas fa-times-circle mr-1"></i> Ditolak
                                                </span>
                                            <?php } ?>
                                        </td>

                                        <td class="text-center">
                                            <?php if ($status == 'Pending') { ?>
                                                <button onclick="prosesApproval(<?= $id ?>, 'terima')"
                                                    class="btn btn-success btn-sm btn-round shadow-sm mr-1" data-toggle="tooltip"
                                                    title="Setujui Pengajuan">
                                                    <i class="fas fa-check"></i>
                                                </button>

                                                <button onclick="prosesApproval(<?= $id ?>, 'tolak')"
                                                    class="btn btn-danger btn-sm btn-round shadow-sm" data-toggle="tooltip"
                                                    title="Tolak Pengajuan">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            <?php } elseif ($status == 'Disetujui' || $status == 'Diterima') { ?>
                                                <small class="text-muted font-weight-bold"><i
                                                        class="fas fa-check-circle text-success"></i> Selesai</small>
                                            <?php } else { ?>
                                                <small class="text-muted">Closed</small>
                                            <?php } ?>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });

        function prosesApproval(id, aksi) {
            let judul, pesan, icon, warnaConfirm;

            if (aksi === 'terima') {
                judul = "Setujui Pengajuan?";
                pesan = "Setelah box disetujui, maka box akan masuk ke bagian Pengisian Box.";
                icon = "question";
                warnaConfirm = "#28a745"; // Hijau
                teksTombol = "Ya, Terima";
            } else {
                judul = "Tolak Pengajuan?";
                pesan = "Pengajuan akan dikembalikan ke user.";
                icon = "warning";
                warnaConfirm = "#dc3545"; // Merah
                teksTombol = "Ya, Tolak";
            }

            // 1. TAMPILKAN KONFIRMASI
            Swal.fire({
                title: judul,
                text: pesan,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: warnaConfirm,
                cancelButtonColor: '#6c757d',
                confirmButtonText: teksTombol,
                cancelButtonText: 'Batal',
                reverseButtons: true, // Tombol aksi di kanan
                backdrop: `
                    rgba(0,0,123,0.4)
                `
            }).then((result) => {
                if (result.isConfirmed) {

                    // 2. TAMPILKAN LOADING (SPINNER BERPUTAR)
                    let timerInterval;
                    Swal.fire({
                        title: 'Sedang Memproses...',
                        html: 'Mohon tunggu, data sedang diupdate.',
                        timer: 1000, // Simulasi loading 1 detik
                        timerProgressBar: true,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading(); // Memunculkan Spinner Loading
                        },
                        willClose: () => {
                            clearInterval(timerInterval);
                        }
                    }).then((result) => {
                        // 3. SETELAH LOADING SELESAI -> MUNCUL SUKSES
                        if (result.dismiss === Swal.DismissReason.timer) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Data berhasil disimpan.',
                                icon: 'success',
                                showConfirmButton: false, // Hilangkan tombol OK
                                timer: 1500 // Otomatis tutup dalam 1.5 detik
                            }).then(() => {
                                // 4. REDIRECT HALAMAN
                                window.location.href = "modules/barang-masuk/proses_approval.php?id=" + id + "&aksi=" + aksi;
                            });
                        }
                    });

                }
            });
        }
    </script>
<?php } ?>