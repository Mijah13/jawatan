<?php
/** @var $data array */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'MyFasiliti';

$currentMonth = date('m');
$currentYear = date('Y');
$selectedMonth = Yii::$app->request->get('month', $currentMonth);
$selectedYear = Yii::$app->request->get('year', $currentYear);
$selectedType = Yii::$app->request->get('type', 'asrama');

$sql = "
    SELECT 
        a.jenis_asrama_id,
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
    FROM tempah_asrama t
    JOIN asrama a ON t.id_asrama = a.id
    WHERE YEAR(t.tarikh_masuk) = :year
      AND MONTH(t.tarikh_masuk) = :month
      AND t.status_tempahan_adminKemudahan IN (3, 5)
      AND t.status_tempahan_pelulus = 2
    GROUP BY a.jenis_asrama_id
";
$command = Yii::$app->db->createCommand($sql);
$command->bindValue(':year', $selectedYear);
$command->bindValue(':month', $selectedMonth);
$berbayarData = $command->queryAll();

$statusPembayaranMap = [];
foreach ($berbayarData as $item) {
    $statusPembayaranMap[$item['jenis_asrama_id']] = $item;
}

// Dapatkan data asrama
$asramaSql = "
    SELECT 
    ja.jenis_bilik AS jenis_bilik,
    a.jenis_asrama_id,
    COUNT(t.id) AS total_bookings,
    SUM(CASE WHEN t.jantina = 0 THEN 1 ELSE 0 END) AS total_male,
    SUM(CASE WHEN t.jantina = 1 THEN 1 ELSE 0 END) AS total_female
FROM tempah_asrama t
JOIN asrama a ON t.id_asrama = a.id
JOIN jenis_asrama ja ON a.jenis_asrama_id = ja.id
WHERE YEAR(t.tarikh_masuk) = :year
  AND MONTH(t.tarikh_masuk) = :month
  AND t.status_tempahan_adminKemudahan IN (3, 5)
  AND t.status_tempahan_pelulus = 2
GROUP BY a.jenis_asrama_id
";

$command = Yii::$app->db->createCommand($asramaSql);
$command->bindValue(':year', $selectedYear);
$command->bindValue(':month', $selectedMonth);
$asramaData = $command->queryAll();

$asramaMap = [];
foreach ($asramaData as $item) {
    $asramaMap[$item['jenis_asrama_id']][] = $item;
}

$fasilitiSql = "
    SELECT 
        jf.nama_fasiliti,
        COUNT(t.id) AS total_bookings
    FROM tempah_fasiliti t
    JOIN fasiliti jf ON t.fasiliti_id = jf.id
    WHERE YEAR(t.tarikh_masuk) = :year
      AND MONTH(t.tarikh_masuk) = :month
      AND t.status_tempahan_adminKemudahan IN (3, 5)
      AND t.status_tempahan_pelulus = 2
    GROUP BY t.fasiliti_id
";


$command = Yii::$app->db->createCommand($fasilitiSql);
$command->bindValue(':year', $selectedYear);
$command->bindValue(':month', $selectedMonth);
$fasilitiData = $command->queryAll();


$sqlFasiliti = "
    SELECT 
        a.nama_fasiliti,
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
    JOIN fasiliti a ON t.fasiliti_id = a.id
    WHERE YEAR(t.tarikh_masuk) = :year
      AND MONTH(t.tarikh_masuk) = :month
      AND t.status_tempahan_adminKemudahan IN (3, 5)
      AND t.status_tempahan_pelulus = 2
    GROUP BY a.nama_fasiliti
";
$command = Yii::$app->db->createCommand($sqlFasiliti);
$command->bindValue(':year', $selectedYear);
$command->bindValue(':month', $selectedMonth);
$fasilitiBerbayarData = $command->queryAll();

$statusPembayaranFasilitiMap = [];
foreach ($fasilitiBerbayarData as $item) {
    $statusPembayaranFasilitiMap[$item['nama_fasiliti']] = $item;
}


?>
<?php
// Persediaan data untuk carta bar
$chartLabels = [];
$chartBerbayar = [];
$chartTidakBerbayar = [];

if ($selectedType === 'asrama') {
    foreach ($asramaData as $row) {
        $jenisId = $row['jenis_asrama_id'];
        $chartLabels[] = $row['jenis_bilik'];

        $chartBerbayar[] = isset($statusPembayaranMap[$jenisId]) ? $statusPembayaranMap[$jenisId]['lulus_berbayar'] : 0;
        $chartTidakBerbayar[] = isset($statusPembayaranMap[$jenisId]) ? $statusPembayaranMap[$jenisId]['lulus_tidak_berbayar'] : 0;
    }
}
elseif ($selectedType === 'fasiliti') {
    foreach ($fasilitiData as $row) {
        $nama = $row['nama_fasiliti'];
        $chartLabels[] = $nama;

        $chartBerbayar[] = isset($statusPembayaranFasilitiMap[$nama]) ? $statusPembayaranFasilitiMap[$nama]['lulus_berbayar'] : 0;
        $chartTidakBerbayar[] = isset($statusPembayaranFasilitiMap[$nama]) ? $statusPembayaranFasilitiMap[$nama]['lulus_tidak_berbayar'] : 0;
    }
}

?>

<?php $form = ActiveForm::begin([
    'method' => 'get',
    'action' => Url::to(['laporan/laporan-tempahan-bulanan']),
]); ?>

<div class="card1 shadow rounded border-0 p-2 mb-4">
<div class="card-body d-flex flex-wrap gap-3 align-items-center justify-content-center">

        <div class="fw-bold">Pilih Bulan:</div>
        <select name="month" id="month" class="form-select form-select-sm" style="width: 110px;">

            <?php for ($m = 1; $m <= 12; $m++): ?>
                <option value="<?= $m ?>" <?= ($selectedMonth == $m) ? 'selected' : '' ?>>
                    <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
                </option>
            <?php endfor; ?>
        </select>

        <div class="fw-bold">Pilih Tahun:</div>
        <select name="year" id="year" class="form-select form-select-sm" style="width: 100px;">

            <?php for ($y = $currentYear; $y >= ($currentYear - 5); $y--): ?>
                <option value="<?= $y ?>" <?= ($selectedYear == $y) ? 'selected' : '' ?>>
                    <?= $y ?>
                </option>
            <?php endfor; ?>
        </select>
         </select>
            <div class="fw-bold">Jenis Laporan:</div>
            <select name="type" id="type" class="form-select form-select-sm" style="width: 150px;" onchange="this.form.submit()">
                <option value="asrama" <?= Yii::$app->request->get('type', 'asrama') === 'asrama' ? 'selected' : '' ?>>Asrama</option>
                <option value="fasiliti" <?= Yii::$app->request->get('type') === 'fasiliti' ? 'selected' : '' ?>>Fasiliti</option>
        </select>

    </div>
</div>
<?php ActiveForm::end(); ?>

<!-- Printable Header -->
<div class="header text-center d-none d-print-block mb-3">
    <img src="<?= Url::to('@web/images/LogoCiast.png', true) ?>" alt="Logo" style="height:80px;">
    <h4 class="mb-1">LAPORAN TEMPAHAN BULANAN</h4>
    <h5 class="mb-1">CIAST SHAH ALAM</h5>
    <p><?= date('F Y', strtotime("$selectedYear-$selectedMonth-01")) ?></p>
</div>

<div class="card shadow rounded border-0 p-4">
    <!-- <h3 class="mb-4 text-center">Laporan Tempahan Bulanan - <?= date('F Y', strtotime("$selectedYear-$selectedMonth-01")) ?></h3> -->

   <!-- Tempahan Asrama -->
<?php if ($selectedType === 'asrama'): ?>
<div class="mb-5">
    <h4 class="text-tajuk">Tempahan Asrama</h4>
    <?php if (!empty($asramaData)): ?>
        <div class="report-table">
            <table class="table table-bordered align-middle mt-3">
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
                    <?php foreach ($asramaData as $row): 
                        $jenis = $row['jenis_asrama_id'];
                        $berbayar = isset($statusPembayaranMap[$jenis]) ? $statusPembayaranMap[$jenis]['lulus_berbayar'] : 0;
                        $tidakBerbayar = isset($statusPembayaranMap[$jenis]) ? $statusPembayaranMap[$jenis]['lulus_tidak_berbayar'] : 0;
                    ?>
                    <tr>
                        <td><?= Html::encode($row['jenis_bilik']) ?></td>
                        <td><?= Html::encode($row['total_bookings']) ?></td>
                        <td><?= Html::encode($row['total_male']) ?></td>
                        <td><?= Html::encode($row['total_female']) ?></td>
                        <td><?= Html::encode($berbayar) ?></td>
                        <td><?= Html::encode($tidakBerbayar) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-5">
    <h5 class="text-tajuk">Carta Tempahan Asrama Mengikut Bulan</h5>
    <canvas id="asramaBarChart" height="100"></canvas>
</div>
    <?php else: ?>
        <div class="alert alert-info mt-3">Tiada tempahan asrama pada bulan ini.</div>
    <?php endif; ?>
</div>


    <!-- Tempahan Fasiliti -->
<?php elseif ($selectedType === 'fasiliti'): ?>
    <h4 class="text-tajuk">Tempahan Fasiliti</h4>
    <?php if (!empty($fasilitiData)): ?>
        <div class="report-table">
            <table class="table table-bordered align-middle mt-3">
                <thead class="custom-header">
                    <tr>
                        <th>Nama Fasiliti</th>
                        <th>Jumlah Tempahan</th>
                        <th>Berbayar</th>
                        <th>Tidak Berbayar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($fasilitiData as $row):
                        $nama = $row['nama_fasiliti'];
                        $berbayar = isset($statusPembayaranFasilitiMap[$nama]) ? $statusPembayaranFasilitiMap[$nama]['lulus_berbayar'] : 0;
                        $tidakBerbayar = isset($statusPembayaranFasilitiMap[$nama]) ? $statusPembayaranFasilitiMap[$nama]['lulus_tidak_berbayar'] : 0;
                        ?>
                        <tr>
                            <td><?= Html::encode($nama) ?></td>
                            <td><?= Html::encode($row['total_bookings']) ?></td>
                            <td><?= Html::encode($berbayar) ?></td>
                            <td><?= Html::encode($tidakBerbayar) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-5">
            <h5 class="text-tajuk">Carta Ringkasan Tempahan Fasiliti Mengikut Bulan</h5>
            <canvas id="fasilitiBarChart" height="80"></canvas>
        </div>
        <?php else: ?>
            <div class="alert alert-info mt-3">Tiada tempahan fasiliti pada bulan ini.</div>
        <?php endif; ?>
<?php endif; ?>
<!-- Print Button -->
<!-- <div class="mt-4 mb-5">
    <button onclick="window.print()" class="btn btn-primary">
        <i class="fas fa-print"></i> Print
    </button>
</div> -->
</div>
    </div>

<?php
$this->registerCss('
    @media print {
        .main-header, .main-footer, .sidebar, .navbar, .pagination,
        button, .btn, .mt-4, .mb-5, form, select, label {
            display: none !important;
        }

        body, .content-wrapper, .container, .card, .content {
            margin: 0 !important;
            padding: 0 !important;
            box-shadow: none !important;
            background: white !important;
        }

        .header {
            display: block !important;
        }

        .table {
            page-break-inside: avoid;
        }

        .table th, .table td {
            font-size: 14px;
        }
    }
');

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

<script>
document.addEventListener("DOMContentLoaded", function() {
    const monthSelect = document.getElementById("month");
    const yearSelect = document.getElementById("year");

    function reloadPage() {
        const selectedMonth = monthSelect.value;
        const selectedYear = yearSelect.value;
        window.location.href = "<?= Url::to(['laporan/laporan-tempahan-bulanan']) ?>?month=" + selectedMonth + "&year=" + selectedYear;
    }

    monthSelect.addEventListener("change", reloadPage);
    yearSelect.addEventListener("change", reloadPage);
});
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<?php if ($selectedType === 'asrama'): ?>
<script>
const ctx = document.getElementById('asramaBarChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($chartLabels) ?>,
        datasets: [
            {
                label: 'Berbayar',
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                data: <?= json_encode($chartBerbayar) ?>
            },
            {
                label: 'Tidak Berbayar',
                backgroundColor: 'rgba(255, 99, 132, 0.7)',
                data: <?= json_encode($chartTidakBerbayar) ?>
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' },
            title: {
                display: false,
                text: 'Carta Tempahan Bulanan'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                precision: 0
            }
        }
    }
});
</script>

<?php elseif ($selectedType === 'fasiliti'): ?>
    <script>
const ctx = document.getElementById('fasilitiBarChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($chartLabels) ?>,
        datasets: [
            {
                label: 'Berbayar',
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                data: <?= json_encode($chartBerbayar) ?>
            },
            {
                label: 'Tidak Berbayar',
                backgroundColor: 'rgba(255, 99, 132, 0.7)',
                data: <?= json_encode($chartTidakBerbayar) ?>
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' },
            title: {
                display: false,
                text: 'Carta Tempahan Bulanan'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                precision: 0
            }
        }
    }
});
</script>

<?php endif; ?>
