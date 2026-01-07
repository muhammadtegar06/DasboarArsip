<?php
// mencegah direct access file PHP
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('location: 404.html');
} else {
    // Menampilkan pesan notifikasi (Alerts)
    if (isset($_GET['pesan'])) {
        if ($_GET['pesan'] == 1) {
            echo '<div class="alert alert-notify alert-success alert-dismissible fade show" role="alert">
                    <span data-notify="icon" class="fas fa-check"></span> 
                    <span data-notify="title" class="text-success">Sukses!</span> 
                    <span data-notify="message">Data dokumen berhasil disimpan.</span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
        } elseif ($_GET['pesan'] == 2) {
            echo '<div class="alert alert-notify alert-success alert-dismissible fade show" role="alert">
                    <span data-notify="icon" class="fas fa-check"></span> 
                    <span data-notify="title" class="text-success">Sukses!</span> 
                    <span data-notify="message">Data dokumen berhasil diubah.</span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
        } elseif ($_GET['pesan'] == 3) {
            echo '<div class="alert alert-notify alert-success alert-dismissible fade show" role="alert">
                    <span data-notify="icon" class="fas fa-check"></span> 
                    <span data-notify="title" class="text-success">Sukses!</span> 
                    <span data-notify="message">Data dokumen berhasil dihapus.</span>
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
                    <h4 class="page-title text-white"><i class="fas fa-folder-open mr-2"></i>Data Dokumen Arsip</h4>
                    <ul class="breadcrumbs">
                        <li class="nav-home"><a href="?module=dashboard"><i class="flaticon-home text-white"></i></a></li>
                        <li class="separator"><i class="flaticon-right-arrow"></i></li>
                        <li class="nav-item"><a href="#" class="text-white">Arsip</a></li>
                        <li class="separator"><i class="flaticon-right-arrow"></i></li>
                        <li class="nav-item"><a>Data Dokumen</a></li>
                    </ul>
                </div>
                <div class="ml-md-auto py-2 py-md-0">
                    <a href="?module=form_entri_dokumen" class="btn btn-secondary btn-round">
                        <span class="btn-label"><i class="fa fa-plus mr-2"></i></span> Entri Dokumen Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-inner mt--5">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Daftar Arsip Dokumen</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="display table table-bordered table-striped table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" width="5%">No.</th>
                                <th class="text-center">Kode Bantex</th>
                                <th class="text-center">Divisi</th>
                                <th class="text-center">Nama Dokumen</th>
                                <th class="text-center">Periode (Tahun)</th>
                                <th class="text-center" width="15%">Edit File</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // --- DATA DUMMY (Pengganti Database Sementara) ---
                            $dummy_data = [
                                [
                                    "bantex" => "B-001-A",
                                    "divisi" => "Keuangan",
                                    "nama" => "Laporan Keuangan Tahunan 2023",
                                    "tahun" => "2023"
                                ],
                                [
                                    "bantex" => "B-002-C",
                                    "divisi" => "HRD",
                                    "nama" => "Rekap Absensi Karyawan Q1",
                                    "tahun" => "2024"
                                ],
                                [
                                    "bantex" => "B-005-A",
                                    "divisi" => "IT",
                                    "nama" => "Dokumentasi Topologi Jaringan",
                                    "tahun" => "2022"
                                ],
                                [
                                    "bantex" => "B-010-F",
                                    "divisi" => "Marketing",
                                    "nama" => "Kontrak Kerjasama Vendor Iklan",
                                    "tahun" => "2024"
                                ],
                                [
                                    "bantex" => "B-003-B",
                                    "divisi" => "Operasional",
                                    "nama" => "SOP Gudang & Logistik",
                                    "tahun" => "2021"
                                ]
                            ];

                            $no = 1;
                            // Loop menggunakan Data Dummy
                            foreach ($dummy_data as $data) { ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td class="text-center font-weight-bold text-primary"><?php echo $data['bantex']; ?></td>
                                    <td><?php echo $data['divisi']; ?></td>
                                    <td><?php echo $data['nama']; ?></td>
                                    <td class="text-center"><span class="badge badge-count"><?php echo $data['tahun']; ?></span></td>
                                    <td class="text-center">
                                        <div class="form-button-action">
                                            <a href="#" data-toggle="tooltip" title="Edit Data" class="btn btn-link btn-primary btn-lg">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="#" data-toggle="tooltip" title="Hapus Data" class="btn btn-link btn-danger" onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php } ?>