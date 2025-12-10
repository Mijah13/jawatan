<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$currentYear = date('Y');
$selectedYear = Yii::$app->request->get('year', $currentYear);
$selectedType = Yii::$app->request->get('type', 'asrama');


// Dapatkan data pembayaran asrama
$sql = "
    SELECT 
        MONTH(t.tarikh_masuk) AS bulan,
        a.jenis_asrama_id,
        ja.jenis_bilik,
        SUM(CASE 
                WHEN t.status_tempahan_adminKemudahan = 3 
                     AND t.status_tempahan_pelulus = 2 
                     AND t.status_pembayaran = 1 
                THEN 1 ELSE 0 
            END) AS lulus_tidak_berbayar,
        SUM(CASE 
                WHEN t.status_tempahan_adminKemudahan = 5 
                     AND t.status_tempahan_pelulus = 2 
                     AND t.status_pembayaran = 3 
                THEN 1 ELSE 0 
            END) AS lulus_berbayar,
        SUM(CASE WHEN t.jantina = 0 THEN 1 ELSE 0 END) AS total_male,
        SUM(CASE WHEN t.jantina = 1 THEN 1 ELSE 0 END) AS total_female,
        COUNT(t.id) AS total_bookings
    FROM tempah_asrama t
    JOIN asrama a ON t.id_asrama = a.id
    JOIN jenis_asrama ja ON a.jenis_asrama_id = ja.id
    WHERE YEAR(t.tarikh_masuk) = :year
      AND t.status_tempahan_adminKemudahan IN (3, 5)
      AND t.status_tempahan_pelulus = 2
    GROUP BY bulan, a.jenis_asrama_id
    ORDER BY bulan ASC
";

$command = Yii::$app->db->createCommand($sql);
$command->bindValue(':year', $selectedYear);
$asramaData = $command->queryAll();

// Dapatkan data fasiliti
$sqlFasiliti = "
    SELECT 
        MONTH(t.tarikh_masuk) AS bulan,
        f.nama_fasiliti,
        COUNT(t.id) AS total_bookings,
        SUM(CASE 
                WHEN t.status_tempahan_adminKemudahan = 3 
                     AND t.status_tempahan_pelulus = 2 
                     AND t.status_pembayaran = 1 
                THEN 1 ELSE 0 
            END) AS lulus_tidak_berbayar,
        SUM(CASE 
                WHEN t.status_tempahan_adminKemudahan = 5 
                     AND t.status_tempahan_pelulus = 2 
                     AND t.status_pembayaran = 3 
                THEN 1 ELSE 0 
            END) AS lulus_berbayar
    FROM tempah_fasiliti t
    JOIN fasiliti f ON t.fasiliti_id = f.id
    WHERE YEAR(t.tarikh_masuk) = :year
      AND t.status_tempahan_adminKemudahan IN (3, 5)
      AND t.status_tempahan_pelulus = 2
    GROUP BY bulan, f.nama_fasiliti
    ORDER BY bulan ASC
";

$command = Yii::$app->db->createCommand($sqlFasiliti);
$command->bindValue(':year', $selectedYear);
$fasilitiData = $command->queryAll();

?>

<!-- <h1><?= Html::encode("Laporan Tempahan Tahunan - Tahun $selectedYear") ?></h1> -->

<!-- Pilihan Tahun -->
<div class="form-group">
    <?= Html::beginForm(['laporan/laporan-tempahan-tahunan'], 'get') ?>
    <div class="card1 shadow rounded border-0 p-2 mb-4">
        <div class="card-body d-flex flex-wrap gap-3 align-items-center justify-content-center">
            <div class="fw-bold">Laporan Tempahan Tahunan bagi Tahun:</div>
            <select name="year" id="year" class="form-select form-select-sm" style="width: 100px;" onchange="this.form.submit()">
            <?php for ($y = $currentYear; $y >= ($currentYear - 5); $y--): ?>
                <option value="<?= $y ?>" <?= ($selectedYear == $y) ? 'selected' : '' ?>>
                    <?= $y ?>
                </option>
            <?php endfor; ?>
            </select>
            <div class="fw-bold">Jenis Laporan:</div>
            <select name="type" id="type" class="form-select form-select-sm" style="width: 150px;" onchange="this.form.submit()">
                <option value="asrama" <?= Yii::$app->request->get('type', 'asrama') === 'asrama' ? 'selected' : '' ?>>Asrama</option>
                <option value="fasiliti" <?= Yii::$app->request->get('type') === 'fasiliti' ? 'selected' : '' ?>>Fasiliti</option>
            </select>

        </div>
    </div>
    <?= Html::endForm() ?>
</div>

<?php
        // Group data ikut bulan
        $groupedData = [];
        foreach ($asramaData as $row) {
            $bulan = (int)$row['bulan'];
            $groupedData[$bulan][] = $row;
        }

        // Bulan-bulan dalam Bahasa Melayu
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Mac', 4 => 'April',
            5 => 'Mei', 6 => 'Jun', 7 => 'Julai', 8 => 'Ogos',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Disember',
        ];
        ?>
        <?php
// Persediaan data untuk carta bar
$chartLabels = [];
$chartBerbayar = [];
$chartTidakBerbayar = [];

foreach (range(1, 12) as $bulan) {
    $chartLabels[] = $namaBulan[$bulan];
    $totalBerbayar = 0;
    $totalTidakBerbayar = 0;

    if (isset($groupedData[$bulan])) {
        foreach ($groupedData[$bulan] as $data) {
            $totalBerbayar += $data['lulus_berbayar'];
            $totalTidakBerbayar += $data['lulus_tidak_berbayar'];
        }
    }

    $chartBerbayar[] = $totalBerbayar;
    $chartTidakBerbayar[] = $totalTidakBerbayar;
}
?>
<?php
$groupedFasiliti = [];

foreach ($fasilitiData as $row) {
    $bulan = $row['bulan'];
    if (!isset($groupedFasiliti[$bulan])) {
        $groupedFasiliti[$bulan] = [];
    }
    $groupedFasiliti[$bulan][] = $row;
}

$chartFasilitiLabels = [];
$chartFasilitiBerbayar = [];
$chartFasilitiTidakBerbayar = [];

foreach (range(1, 12) as $bulan) {
    $chartFasilitiLabels[] = $namaBulan[$bulan];
    $totalBerbayar = 0;
    $totalTidakBerbayar = 0;

    if (isset($groupedFasiliti[$bulan])) {
        foreach ($groupedFasiliti[$bulan] as $data) {
            $totalBerbayar += $data['lulus_berbayar'];
            $totalTidakBerbayar += $data['lulus_tidak_berbayar'];
        }
    }

    $chartFasilitiBerbayar[] = $totalBerbayar;
    $chartFasilitiTidakBerbayar[] = $totalTidakBerbayar;
}
?>

<div class="card shadow rounded border-0 p-4">
<!-- Tempahan Asrama -->
 <?php if ($selectedType === 'asrama'): ?>
<div class="mb-5">
    <h4 class="text-tajuk">Tempahan Asrama</h4>
    <?php if (!empty($asramaData)): ?>
        <?php foreach ($groupedData as $bulan => $rows): ?>
            <div class="mt-4">
                <h6 class="text-bulan"><strong>Bulan: <?= $namaBulan[$bulan] ?></strong></h6>
                <div class="report-table">
                    <table class="table table-bordered align-middle mt-2">
                        <thead class="custom-header">
                            <tr>
                                <th>Jenis Bilik</th>
                                <th>Jumlah Tempahan</th>
                                <th>Lelaki</th>
                                <th>Perempuan</th>
                                <th>Berbayar</th>
                                <th>Tidak Berbayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rows as $row): ?>
                            <tr>
                                <td><?= Html::encode($row['jenis_bilik']) ?></td>
                                <td><?= Html::encode($row['total_bookings']) ?></td>
                                <td><?= Html::encode($row['total_male']) ?></td>
                                <td><?= Html::encode($row['total_female']) ?></td>
                                <td><?= Html::encode($row['lulus_berbayar']) ?></td>
                                <td><?= Html::encode($row['lulus_tidak_berbayar']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="mt-5">
    <!-- <h5 class="text-tajuk">Carta Tempahan Asrama Mengikut Bulan</h5> -->
    <canvas id="asramaBarChart" height="100"></canvas>
</div>

    <?php else: ?>
        <div class="alert alert-info mt-3">Tiada tempahan asrama pada tahun ini.</div>
    <?php endif; ?>
</div>

<!-- Tempahan Fasiliti -->

    
<?php elseif ($selectedType === 'fasiliti'): ?>
    <h4 class="text-tajuk">Tempahan Fasiliti</h4>
    <?php if (!empty($fasilitiData)): ?>
        <?php foreach ($groupedFasiliti as $bulan => $rows): ?>
            <h5 class="mt-4">
                <h6 class="text-bulan"><strong>Bulan: <?= $namaBulan[$bulan] ?></strong></h6>
            <div class="report-table">
                <table class="table table-bordered align-middle">
                    <thead class="custom-header">
                        <tr>
                            <th>Nama Fasiliti</th>
                            <th>Jumlah Tempahan</th>
                            <th>Berbayar</th>
                            <th>Tidak Berbayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= Html::encode($row['nama_fasiliti']) ?></td>
                            <td><?= Html::encode($row['total_bookings']) ?></td>
                            <td><?= Html::encode($row['lulus_berbayar']) ?></td>
                            <td><?= Html::encode($row['lulus_tidak_berbayar']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>
        <div class="mt-5">
    <!-- <h5 class="text-tajuk">Carta Ringkasan Tempahan Fasiliti Mengikut Bulan</h5> -->
    <canvas id="fasilitiChart" height="80"></canvas>
</div>

    <?php else: ?>
        <div class="alert alert-info mt-3">Tiada tempahan fasiliti pada tahun ini.</div>
    <?php endif; ?>
</div>
<?php endif; ?>
<?php
$this->registerCss('
    .text-tajuk {
        font-size: 1.5rem; /* Adjust size */
        font-weight: bold; /* Make it bold */
        color:rgb(32, 42, 111); /* Text color */
    }
    .card1 {
        // background-color:rgb(68, 68, 68); /* Warna latar belakang */
        background-color:rgb(177, 199, 222);
        border: 1px solidrgb(13, 19, 14); /* Warna border */
        height: 50px;
    }
        
    //     .fw-bold {
    //     color: white !important;
    // }

    .text-tajuk {
        color:rgb(8, 49, 85);
        text-size: 20px;
        font;

    }
    .custom-header th {
        background-color: #e0e0e0; /* warna neutral untuk header */
        font-weight: bold;
        color: black;
        text-align: center;
    }

    td {
        text-align: center;
    }

    .report-table {
    width: 100%;
    table-layout: fixed;
    border-collapse: collapse;
    }

    .report-table th,
    .report-table td {
    text-align: center;
    padding: 8px;
    border: 1px solid #ddd;
    font-size: 14px;
    // word-wrap: break-word; /* Ini penting kalau ada nama panjang */
    }

    .report-table th:nth-child(1),
    .report-table td:nth-child(1) {
    width: 400px;
    text-align: left; /* Optional, bagi nama fasiliti align kiri */
    }



');
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php if ($selectedType === 'asrama'): ?>
<script>
    const ctx = document.getElementById('asramaBarChart').getContext('2d');

    const asramaChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($chartLabels) ?>,
            datasets: [
                {
                    label: 'Berbayar',
                    data: <?= json_encode($chartBerbayar) ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Tidak Berbayar',
                    data: <?= json_encode($chartTidakBerbayar) ?>,
                    backgroundColor: 'rgba(255, 159, 64, 0.7)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    },
                    title: {
                        display: true,
                        text: 'Bilangan Tempahan'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Bulan'
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                },
                title: {
                    display: true,
                    text: 'Ringkasan Tempahan Asrama Tahunan (<?= $selectedYear ?>)'
                }
            }
        }
    });
</script>
<?php elseif ($selectedType === 'fasiliti'): ?>
    <script>
    const fasilitiChart = document.getElementById('fasilitiChart').getContext('2d');
    new Chart(fasilitiChart, {
        type: 'bar',
        data: {
            labels: <?= json_encode($chartFasilitiLabels) ?>,
            datasets: [
                {
                    label: 'Berbayar',
                    data: <?= json_encode($chartFasilitiBerbayar) ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.8)'
                },
                {
                    label: 'Tidak Berbayar',
                    data: <?= json_encode($chartFasilitiTidakBerbayar) ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.8)'
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: 'Ringkasan Tempahan Fasiliti Tahunan (<?= $selectedYear ?>)' }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    },
                    title: {
                        display: true,
                        text: 'Bilangan Tempahan'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Bulan'
                    }
                }
            },
        }
    });

</script>
<?php endif; ?>
