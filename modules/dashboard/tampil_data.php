<?php
// mencegah direct access file PHP
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('location: 404.html');
} else {
    // --- 1. LOGIKA MENANGKAP INPUT FILTER (SUDAH DIPERBAIKI) ---
    if (isset($_POST['filter'])) {
        // Hanya menangkap tahun, jika kosong default ke tahun saat ini
        $tahun_pilih = isset($_POST['tahun']) ? $_POST['tahun'] : date('Y');
    } else {
        $tahun_pilih = date('Y'); // Default tahun ini
    }

    $tahun_filter = empty($tahun_pilih) ? date('Y') : $tahun_pilih;

    // --- 2. QUERY KARTU STATISTIK (CARD) ---
    // A. Total Pengajuan
    $q_pengajuan = mysqli_query($mysqli, "SELECT COUNT(*) as total FROM tbl_pengajuan");
    $tot_pengajuan = mysqli_fetch_assoc($q_pengajuan)['total'] ?? 0;

    // B. Total Box To Send / Terkirim
    $q_box = mysqli_query($mysqli, "SELECT SUM(jumlah_box) as total FROM tbl_pengajuan WHERE status IN ('To Send', 'Telah Dikirim')");
    $tot_box_kirim = mysqli_fetch_assoc($q_box)['total'] ?? 0;

    // C. Total Dokumen Digital
    $q_dokumen = mysqli_query($mysqli, "SELECT COUNT(*) as total FROM tbl_dokumen");
    $tot_dokumen = mysqli_fetch_assoc($q_dokumen)['total'] ?? 0;

    // D. Total Bantex Terdaftar
    $q_bantex = mysqli_query($mysqli, "SELECT COUNT(*) as total FROM tbl_bantex");
    $tot_bantex = mysqli_fetch_assoc($q_bantex)['total'] ?? 0;


    // --- 3. QUERY GRAFIK (PER BULAN DALAM 1 TAHUN) ---
    $data_diajukan = [];
    $data_dikirim = [];

    for ($m = 1; $m <= 12; $m++) {
        $bulan_sql = str_pad($m, 2, '0', STR_PAD_LEFT);

        // Box Diajukan bulan ini
        $q_chart1 = mysqli_query($mysqli, "SELECT SUM(jumlah_box) as total FROM tbl_pengajuan WHERE MONTH(tanggal_pengajuan) = '$bulan_sql' AND YEAR(tanggal_pengajuan) = '$tahun_filter'");
        $data_diajukan[] = (int) (mysqli_fetch_assoc($q_chart1)['total'] ?? 0);

        // Box To Send / Dikirim bulan ini
        $q_chart2 = mysqli_query($mysqli, "SELECT SUM(jumlah_box) as total FROM tbl_pengajuan WHERE status IN ('To Send', 'Telah Dikirim') AND MONTH(tanggal_pengajuan) = '$bulan_sql' AND YEAR(tanggal_pengajuan) = '$tahun_filter'");
        $data_dikirim[] = (int) (mysqli_fetch_assoc($q_chart2)['total'] ?? 0);
    }
    ?>

    <style>
        /* Desain Scrollbar untuk Aktivitas agar rapi dan elegan */
        .activity-scroll {
            max-height: 380px;
            overflow-y: auto;
            padding-right: 15px;
        }

        .activity-scroll::-webkit-scrollbar {
            width: 5px;
        }

        .activity-scroll::-webkit-scrollbar-track {
            background: #f8fafc;
            border-radius: 10px;
        }

        .activity-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .activity-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>

    <div class="panel-header bg-secondary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-top flex-column flex-md-row">
                <div class="page-header text-white">
                    <h4 class="page-title text-white"><i class="fas fa-home mr-2"></i> Dashboard</h4>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <h2 class="text-white pb-2 fw-bold">Selamat Datang, Admin!</h2>
            </div>
        </div>
    </div>

    <div class="page-inner mt--5">
        <div class="row">
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                    <i class="fas fa-file-signature"></i>
                                </div>
                            </div>
                            <div class="col col-stats ml-3 ml-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Pengajuan</p>
                                    <h4 class="card-title"><?= number_format($tot_pengajuan, 0, ',', '.') ?>
                                        <small>Trx</small>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="fas fa-truck-loading"></i>
                                </div>
                            </div>
                            <div class="col col-stats ml-3 ml-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Box To Send</p>
                                    <h4 class="card-title"><?= number_format($tot_box_kirim, 0, ',', '.') ?>
                                        <small>Box</small>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-danger bubble-shadow-small">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                            </div>
                            <div class="col col-stats ml-3 ml-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Dokumen Digital</p>
                                    <h4 class="card-title"><?= number_format($tot_dokumen, 0, ',', '.') ?>
                                        <small>File</small>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                            </div>
                            <div class="col col-stats ml-3 ml-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Total Bantex</p>
                                    <h4 class="card-title"><?= number_format($tot_bantex, 0, ',', '.') ?>
                                        <small>Label</small>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title">Grafik Volume Box Arsip Tahun <?= htmlspecialchars($tahun_filter) ?>
                            </div>
                            <div class="card-tools">
                                <form action="?module=dashboard" method="post" class="d-flex">
                                    <select name="tahun" class="form-control form-control-sm mr-2" required>
                                        <option value="">Pilih Tahun</option>
                                        <?php
                                        // Looping tahun otomatis dari 3 tahun lalu sampai tahun depan
                                        $thn_skr = date('Y');
                                        for ($x = $thn_skr - 3; $x <= $thn_skr + 1; $x++) {
                                            $selected = ($x == $tahun_filter) ? 'selected' : '';
                                            echo "<option value='$x' $selected>$x</option>";
                                        }
                                        ?>
                                    </select>
                                    <button type="submit" name="filter" class="btn btn-info btn-border btn-round btn-sm">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="min-height: 375px">
                            <canvas id="statisticsChart"></canvas>
                        </div>
                        <div id="myChartLegend"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Log Aktivitas Terbaru</div>
                    </div>
                    <div class="card-body pb-0">
                        <div class="d-flex">
                            <div class="flex-1 pt-1 ml-2">
                                <h6 class="fw-bold mb-1">Status Pengiriman</h6>
                                <small class="text-muted">Aktivitas update status terakhir</small>
                            </div>
                            <div class="d-flex ml-auto align-items-center">
                                <h3 class="text-info fw-bold">Realtime</h3>
                            </div>
                        </div>
                        <div class="separator-dashed"></div>

                        <div class="activity-scroll">
                            <ol class="activity-feed mb-0">
                                <?php
                                // Ambil 20 aktivitas terbaru (dinaikkan dari 5 ke 20 agar bisa discroll)
                                $q_log = mysqli_query($mysqli, "
                                    SELECT h.waktu, h.keterangan, p.no_pengajuan, d.singkatan_divisi
                                    FROM tbl_history_pengiriman h
                                    JOIN tbl_pengiriman pg ON h.id_pengiriman = pg.id
                                    JOIN tbl_pengajuan p ON pg.id_pengajuan = p.id
                                    JOIN tbl_divisi d ON p.id_divisi = d.id
                                    ORDER BY h.waktu DESC LIMIT 20
                                ");

                                if (mysqli_num_rows($q_log) > 0) {
                                    while ($log = mysqli_fetch_assoc($q_log)) {
                                        $waktu_indo = date('d M, H:i', strtotime($log['waktu']));

                                        // Beri warna dot tergantung kata kuncinya
                                        $color = 'primary';
                                        $ket = strtolower($log['keterangan']);
                                        if (strpos($ket, 'send') !== false || strpos($ket, 'kirim') !== false)
                                            $color = 'success';
                                        if (strpos($ket, 'cancel') !== false || strpos($ket, 'batal') !== false)
                                            $color = 'danger';

                                        ?>
                                        <li class="feed-item feed-item-<?= $color ?>">
                                            <time class="date" datetime="<?= $log['waktu'] ?>"><?= $waktu_indo ?></time>
                                            <span class="text">
                                                <strong><?= htmlspecialchars($log['no_pengajuan']) ?>
                                                    (<?= htmlspecialchars($log['singkatan_divisi']) ?>)</strong> <br>
                                                <?= htmlspecialchars($log['keterangan']) ?>
                                            </span>
                                        </li>
                                        <?php
                                    }
                                } else {
                                    echo '<li class="feed-item feed-item-secondary"><span class="text text-muted">Belum ada log aktivitas pengiriman.</span></li>';
                                }
                                ?>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var ctx = document.getElementById('statisticsChart').getContext('2d');

            var statisticsChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des"],
                    datasets: [
                        {
                            label: "Box Diajukan",
                            borderColor: '#f3545d',
                            pointBackgroundColor: 'rgba(243, 84, 93, 0.6)',
                            pointRadius: 0,
                            backgroundColor: 'rgba(243, 84, 93, 0.4)',
                            legendColor: '#f3545d',
                            fill: true,
                            borderWidth: 2,
                            data: <?php echo json_encode($data_diajukan); ?>
                        },
                        {
                            label: "Box Dikirim (To Send)",
                            borderColor: '#1d7af3',
                            pointBackgroundColor: 'rgba(29, 122, 243, 0.6)',
                            pointRadius: 0,
                            backgroundColor: 'rgba(29, 122, 243, 0.4)',
                            legendColor: '#1d7af3',
                            fill: true,
                            borderWidth: 2,
                            data: <?php echo json_encode($data_dikirim); ?>
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false
                    },
                    tooltips: {
                        bodySpacing: 4,
                        mode: "nearest",
                        intersect: 0,
                        position: "nearest",
                        xPadding: 10,
                        yPadding: 10,
                        caretPadding: 10
                    },
                    layout: {
                        padding: { left: 5, right: 5, top: 15, bottom: 15 }
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                fontStyle: "500",
                                beginAtZero: true,
                                maxTicksLimit: 5,
                                padding: 10
                            },
                            gridLines: {
                                drawTicks: false,
                                display: false
                            }
                        }],
                        xAxes: [{
                            gridLines: {
                                zeroLineColor: "transparent"
                            },
                            ticks: {
                                padding: 10,
                                fontStyle: "500"
                            }
                        }]
                    },
                    legendCallback: function (chart) {
                        var text = [];
                        text.push('<ul class="' + chart.id + '-legend html-legend">');
                        for (var i = 0; i < chart.data.datasets.length; i++) {
                            text.push('<li><span style="background-color:' + chart.data.datasets[i].legendColor + '"></span>');
                            if (chart.data.datasets[i].label) {
                                text.push(chart.data.datasets[i].label);
                            }
                            text.push('</li>');
                        }
                        text.push('</ul>');
                        return text.join('');
                    }
                }
            });

            var myLegendContainer = document.getElementById("myChartLegend");
            myLegendContainer.innerHTML = statisticsChart.generateLegend();
            var legendItems = myLegendContainer.getElementsByTagName('li');
            for (var i = 0; i < legendItems.length; i += 1) {
                legendItems[i].addEventListener("click", legendClickCallback, false);
            }
        });
    </script>
<?php } ?>