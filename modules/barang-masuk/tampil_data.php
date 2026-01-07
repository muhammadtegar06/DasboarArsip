<?php
// Tampil Data Barang Masuk (Versi Elegan: View Only + Delete)
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('location: 404.html');
} else {
    ?>
    <div class="panel-header bg-secondary-gradient">
        <div class="page-inner py-45">
            <div class="d-flex align-items-left align-items-md-top flex-column flex-md-row">
                <div class="page-header text-white">
                    <h4 class="page-title text-white"><i class="fas fa-layer-group mr-2"></i> Monitoring Arsip</h4>
                    <ul class="breadcrumbs">
                        <li class="nav-home"><a href="?module=dashboard"><i class="flaticon-home text-white"></i></a></li>
                        <li class="separator"><i class="flaticon-right-arrow"></i></li>
                        <li class="nav-item"><a href="?module=barang_masuk" class="text-white">Data Box</a></li>
                    </ul>
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
                    <h4 class="card-title font-weight-bold text-dark">Riwayat Pengajuan Box</h4>
                    <span class="badge badge-light text-muted">Real-time Update</span>
                </div>
            </div>
            <div class="card-body px-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center py-3" width="5%" style="border-top:none;">#</th>
                                <th class="py-3" style="border-top:none;">Informasi Divisi & Lokasi</th>
                                <th class="text-center py-3" style="border-top:none;">Volume</th>
                                <th class="py-3" width="25%" style="border-top:none;">Status Terkini</th>
                                <th class="text-center py-3" width="10%" style="border-top:none;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tabelBody">
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        const STORAGE_KEY = 'db_arsip_elegan_v1'; 

        $(document).ready(function () {
            initDummyData(); 
            renderTable();   
        });

        // 1. DATA DUMMY (Campuran Status untuk Demo Tampilan)
        function initDummyData() {
            let existingData = localStorage.getItem(STORAGE_KEY);
            if (!existingData || JSON.parse(existingData).length === 0) {
                let dummyData = [
                    {
                        divisi: "DTPI - Divisi Satuan Pengawasan Intern",
                        lokasi: "Head Office (HO)",
                        tanggal: "30 Des 2025",
                        total_box: 2,
                        total_bantex: 12,
                        status: 'pending', 
                        history_time: null
                    },
                    {
                        divisi: "DSDM - Divisi Operasional SDM",
                        lokasi: "Gudang Sentral",
                        tanggal: "29 Des 2025",
                        total_box: 5,
                        total_bantex: 30,
                        status: 'accepted',
                        history_time: "29 Des 2025, 14:30 WIB"
                    },
                    {
                        divisi: "DHKM - Divisi Hukum",
                        lokasi: "Head Office (HO)",
                        tanggal: "27 Des 2025",
                        total_box: 1,
                        total_bantex: 6,
                        status: 'rejected',
                        history_time: "27 Des 2025, 16:45 WIB"
                    },
                    {
                        divisi: "DINF - Divisi Infrastruktur",
                        lokasi: "Head Office (HO)",
                        tanggal: "26 Des 2025",
                        total_box: 3,
                        total_bantex: 18,
                        status: 'accepted',
                        history_time: "26 Des 2025, 09:00 WIB"
                    }
                ];
                localStorage.setItem(STORAGE_KEY, JSON.stringify(dummyData));
            }
        }

        // 2. RENDER TABEL ELEGAN
        function renderTable() {
            let dataArsip = JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
            let html = '';

            if (dataArsip.length === 0) {
                html = '<tr><td colspan="5" class="text-center py-5 text-muted">Belum ada data pengajuan.</td></tr>';
            } else {
                dataArsip.forEach((item, index) => {
                    
                    let statusView = '';

                    // --- LOGIKA DESAIN STATUS ---
                    if (item.status === 'pending') {
                        statusView = `
                            <div class="d-flex align-items-center">
                                <div class="icon-preview bg-warning-light text-warning mr-3 rounded-circle d-flex justify-content-center align-items-center" style="width:35px; height:35px;">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 font-weight-bold text-warning">Menunggu Persetujuan</h6>
                                    <small class="text-muted">Diajukan: ${item.tanggal}</small>
                                </div>
                            </div>
                        `;
                    } else if (item.status === 'accepted') {
                        statusView = `
                            <div class="d-flex align-items-center">
                                <div class="icon-preview bg-success-light text-success mr-3 rounded-circle d-flex justify-content-center align-items-center" style="width:35px; height:35px;">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 font-weight-bold text-success">Disetujui</h6>
                                    <small class="text-muted" style="font-size: 11px;">
                                        ${item.history_time}
                                    </small>
                                </div>
                            </div>
                        `;
                    } else if (item.status === 'rejected') {
                        statusView = `
                            <div class="d-flex align-items-center">
                                <div class="icon-preview bg-danger-light text-danger mr-3 rounded-circle d-flex justify-content-center align-items-center" style="width:35px; height:35px;">
                                    <i class="fas fa-times"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 font-weight-bold text-danger">Ditolak</h6>
                                    <small class="text-muted" style="font-size: 11px;">
                                        ${item.history_time}
                                    </small>
                                </div>
                            </div>
                        `;
                    }

                    // --- RENDER BARIS ---
                    html += `
                    <tr style="border-bottom: 1px solid #f1f1f1;">
                        <td class="text-center text-muted">${index + 1}</td>
                        
                        <td class="py-3">
                            <div class="font-weight-bold text-dark" style="font-size:14px;">${item.divisi}</div>
                            <div class="small text-muted mt-1">
                                <i class="fas fa-map-marker-alt mr-1 text-secondary"></i> ${item.lokasi}
                            </div>
                        </td>

                        <td class="text-center">
                            <h5 class="mb-0 font-weight-bold text-dark">${item.total_box} Box</h5>
                            <small class="text-muted">${item.total_bantex} Bantex</small>
                        </td>

                        <td class="py-3">
                            ${statusView}
                        </td>

                        <td class="text-center">
                            <button onclick="hapusData(${index})" 
                                class="btn btn-link btn-danger btn-lg p-2" 
                                data-toggle="tooltip" 
                                title="Hapus Pengajuan">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    `;
                });
            }

            $('#tabelBody').html(html);
            $('[data-toggle="tooltip"]').tooltip(); // Aktifkan tooltip Bootstrap
        }

        // FUNGSI HAPUS
        function hapusData(index) {
            swal({
                title: "Batalkan Pengajuan?",
                text: "Data pengajuan ini akan dihapus dari sistem.",
                icon: "warning",
                buttons: ["Batal", "Ya, Hapus"],
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    let dataArsip = JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
                    dataArsip.splice(index, 1);
                    localStorage.setItem(STORAGE_KEY, JSON.stringify(dataArsip));
                    renderTable();
                    swal("Terhapus!", "Pengajuan berhasil dibatalkan.", "success");
                }
            });
        }
    </script>
    
    <style>
        .bg-success-light { background-color: #eafbf2 !important; }
        .bg-warning-light { background-color: #fff9e6 !important; }
        .bg-danger-light  { background-color: #fcecec !important; }
        
        .table-hover tbody tr:hover {
            background-color: #fcfcfc;
        }
    </style>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<?php } ?>