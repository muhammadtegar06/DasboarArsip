<?php
// Tampil Data Barang Masuk (Versi Simulasi Local Storage + 10 Dummy Data)
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('location: 404.html');
} else {
    ?>
    <div class="panel-header bg-secondary-gradient">
        <div class="page-inner py-45">
            <div class="d-flex align-items-left align-items-md-top flex-column flex-md-row">
                <div class="page-header text-white">
                    <h4 class="page-title text-white"><i class="fas fa-sign-in-alt mr-2"></i> Data Box</h4>
                    <ul class="breadcrumbs">
                        <li class="nav-home"><a href="?module=dashboard"><i class="flaticon-home text-white"></i></a></li>
                        <li class="separator"><i class="flaticon-right-arrow"></i></li>
                        <li class="nav-item"><a href="?module=barang_masuk" class="text-white">Data Box</a></li>
                    </ul>
                </div>
                <div class="ml-md-auto py-2 py-md-0">
                    <a href="?module=form_entri_barang_masuk" class="btn btn-secondary btn-round">
                        <span class="btn-label"><i class="fa fa-plus mr-2"></i></span> Entri Data Baru
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-inner mt--5">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Data Box Divisi (Simulasi 10 Data)</div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="basic-datatables" class="display table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="text-center" width="5%">No.</th>
                                <th class="text-center">Divisi</th>
                                <th class="text-center">Tanggal Pengajuan</th>
                                <th class="text-center">Box</th>
                                <th class="text-center">Bantex</th>
                                <th class="text-center" width="25%">Status / Histori</th>
                                <th class="text-center">Aksi</th>
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
        $(document).ready(function () {
            initDummyData(); // Buat 10 data contoh jika kosong
            renderTable();   // Tampilkan tabel
        });

        // 1. BUAT 10 DATA CONTOH (DUMMY) JIKA LOCAL STORAGE KOSONG
        function initDummyData() {
            let existingData = localStorage.getItem('simulasi_db_arsip');

            // Hanya buat data baru jika localStorage kosong
            if (!existingData || JSON.parse(existingData).length === 0) {

                let dummyData = [
                    {
                        divisi: "DTPI - Divisi Satuan Pengawasan Intern",
                        lokasi: "HO",
                        tanggal: "2025-12-30",
                        total_box: 2,
                        total_bantex: 12,
                        status_submit: true,
                        history_time: null,
                        detail_bantex: []
                    },
                    {
                        divisi: "DSDM - Divisi Operasional SDM",
                        lokasi: "Gudang",
                        tanggal: "2025-12-29",
                        total_box: 1,
                        total_bantex: 5,
                        status_submit: true,
                        history_time: "29 Des 2025 14:30:00",
                        detail_bantex: []
                    },
                    {
                        divisi: "DSPN - Divisi Sekretariat Perusahaan",
                        lokasi: "HO",
                        tanggal: "2025-12-28",
                        total_box: 3,
                        total_bantex: 15,
                        status_submit: true,
                        history_time: "",
                        detail_bantex: []
                    },
                    {
                        divisi: "DTIS - Divisi Teknologi Informasi",
                        lokasi: "HO",
                        tanggal: "2025-12-28",
                        total_box: 1,
                        total_bantex: 3,
                        status_submit: true,
                        history_time: null,
                        detail_bantex: []
                    },
                    {
                        divisi: "DHKM - Divisi Hukum",
                        lokasi: "Gudang",
                        tanggal: "2025-12-27",
                        total_box: 5,
                        total_bantex: 28,
                        status_submit: true,
                        history_time: "27 Des 2025 16:45:10",
                        detail_bantex: []
                    },
                    {
                        divisi: "DINF - Divisi Infrastruktur",
                        lokasi: "HO",
                        tanggal: "2025-12-26",
                        total_box: 2,
                        total_bantex: 10,
                        status_submit: false,
                        history_time: null,
                        detail_bantex: []
                    },
                    {
                        divisi: "DPBA - Divisi Perbendaharaan & Anggaran",
                        lokasi: "Gudang",
                        tanggal: "2025-12-25",
                        total_box: 1,
                        total_bantex: 6,
                        status_submit: true,
                        history_time: "25 Des 2025 11:20:05",
                        detail_bantex: []
                    },
                    {
                        divisi: "DPSR - Divisi PSR dan Plasma",
                        lokasi: "HO",
                        tanggal: "2025-12-24",
                        total_box: 4,
                        total_bantex: 20,
                        status_submit: false,
                        history_time: null,
                        detail_bantex: []
                    },
                    {
                        divisi: "DPSN - Divisi Pemasaran",
                        lokasi: "HO",
                        tanggal: "2025-12-23",
                        total_box: 1,
                        total_bantex: 2,
                        status_submit: true,
                        history_time: "23 Des 2025 08:30:00",
                        detail_bantex: []
                    },
                    {
                        divisi: "DPMO - Project Management Office",
                        lokasi: "Gudang",
                        tanggal: "2025-12-22",
                        total_box: 2,
                        total_bantex: 9,
                        status_submit: false,
                        history_time: null,
                        detail_bantex: []
                    }
                ];

                localStorage.setItem('simulasi_db_arsip', JSON.stringify(dummyData));
            }
        }

        // 2. RENDER TABEL DARI LOCAL STORAGE
        function renderTable() {
            let dataArsip = JSON.parse(localStorage.getItem('simulasi_db_arsip')) || [];
            let html = '';

            if (dataArsip.length === 0) {
                html = '<tr><td colspan="7" class="text-center">Data Kosong (Silakan Refresh Browser untuk Load Dummy Data)</td></tr>';
            } else {
                // Loop data
                dataArsip.forEach((item, index) => {

                    let statusHtml = '';

                    // --- LOGIKA TOMBOL SUBMIT VS HISTORI ---
                    if (item.status_submit === true) {
                        statusHtml = `
                            <div class="text-left">
                                <span class="badge badge-success mb-1"><i class="fas fa-check-circle"></i> Terkirim</span><br>
                                <small class="text-muted font-weight-bold">
                                    <i class="fas fa-history"></i> ${item.history_time}
                                </small>
                            </div>
                        `;
                    } else {
                        statusHtml = `
                            <button onclick="prosesSubmit(${index})" class="btn btn-primary btn-round btn-sm shadow-sm">
                                <i class="fas fa-paper-plane mr-1"></i> Submit Sekarang
                            </button>
                        `;
                    }

                    // Render Baris
                    html += `
                    <tr>
                        <td class="text-center">${index + 1}</td>
                        <td class="font-weight-bold text-primary">${item.divisi}</td>
                        <td class="text-center">${item.tanggal}</td>
                        <td class="text-center">
                            <span class="badge badge-count border border-secondary text-secondary">${item.total_box} Box</span>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-info">${item.total_bantex} Bantex</span>
                        </td>
                        <td class="text-center">
                            ${statusHtml}
                        </td>
                        <td class="text-center">
                            <button onclick="hapusData(${index})" class="btn btn-icon btn-round btn-danger btn-xs" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    `;
                });
            }

            $('#tabelBody').html(html);
        }

        // 3. FUNGSI KLIK SUBMIT (REAL TIME)
        function prosesSubmit(index) {
            let dataArsip = JSON.parse(localStorage.getItem('simulasi_db_arsip')) || [];

            // Ambil Waktu Sekarang
            let now = new Date();
            let timeString = now.toLocaleString('id-ID', {
                day: '2-digit', month: 'short', year: 'numeric',
                hour: '2-digit', minute: '2-digit', second: '2-digit'
            });

            // Update Data
            dataArsip[index].status_submit = true;
            dataArsip[index].history_time = timeString;

            // Simpan
            localStorage.setItem('simulasi_db_arsip', JSON.stringify(dataArsip));

            swal("Berhasil!", "Data telah disubmit pada: " + timeString, "success");
            renderTable();
        }

        // 4. FUNGSI HAPUS
        function hapusData(index) {
            swal({
                title: "Hapus Data?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        let dataArsip = JSON.parse(localStorage.getItem('simulasi_db_arsip')) || [];
                        dataArsip.splice(index, 1);
                        localStorage.setItem('simulasi_db_arsip', JSON.stringify(dataArsip));
                        renderTable();
                        swal("Data berhasil dihapus!", { icon: "success", });
                    }
                });
        }
    </script>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<?php } ?>