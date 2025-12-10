<?php
/** @var $data array */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'MyFasiliti';
$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js', ['depends' => [\yii\web\JqueryAsset::class, \yii\web\YiiAsset::class]]);
$currentYear = date('Y');
$selectedYear = Yii::$app->request->get('year', $currentYear);
$currentMonth = date('m');
$selectedMonth = Yii::$app->request->get('month', $currentMonth);

$sql = "
    SELECT 
    ja.jenis_bilik AS jenis_bilik, 
    COUNT(*) AS total_tempahan,
    SUM(CASE WHEN t.status_tempahan_adminKemudahan IN (3, 5) THEN 1 ELSE 0 END) AS total_lulus,
    SUM(CASE WHEN t.status_tempahan_adminKemudahan = 4 THEN 1 ELSE 0 END) AS total_gagal,
    SUM(CASE 
            WHEN t.status_tempahan_adminKemudahan = 3 
                 AND t.status_tempahan_pelulus = 2 
                 AND t.status_pembayaran = 1 
            THEN 1 
            ELSE 0 
        END) AS lulus_tidak_berbayar,
    SUM(CASE 
            WHEN t.status_tempahan_adminKemudahan = 5 
                 AND t.status_tempahan_pelulus = 2 
                 AND t.status_pembayaran = 3 
            THEN 1 
            ELSE 0 
        END) AS lulus_berbayar
FROM tempah_asrama t
JOIN asrama a ON t.id_asrama = a.id
JOIN jenis_asrama ja ON t.jenis_bilik = ja.id
WHERE YEAR(t.tarikh_masuk) = :year
    AND MONTH(t.tarikh_masuk) = :month
    AND t.status_tempahan_adminKemudahan IS NOT NULL
    AND t.status_tempahan_adminKemudahan NOT IN (0, 1, 2)
    AND t.status_tempahan_pelulus IS NOT NULL
    AND t.status_tempahan_pelulus NOT IN (0, 1)
GROUP BY ja.jenis_bilik
ORDER BY ja.jenis_bilik ASC

";


$command = Yii::$app->db->createCommand($sql);
$command->bindValue(':year', $selectedYear);
$command->bindValue(':month', $selectedMonth);
$result = $command->queryAll();

$jenisBilikData = [];
foreach ($result as $row) {
    $jenisBilikData[$row['jenis_bilik']] = [
        'total_tempahan' => $row['total_tempahan'],
        'total_lulus' => $row['total_lulus'],
        'total_gagal' => $row['total_gagal'],
        'lulus_berbayar' => $row['lulus_berbayar'],
        'lulus_tidak_berbayar' => $row['lulus_tidak_berbayar'],
    ];
}

$summary_sql = "
    SELECT 
        COUNT(*) AS total_tempahan,
        SUM(CASE WHEN t.status_tempahan_adminKemudahan IN (3, 5) THEN 1 ELSE 0 END) AS total_lulus,
        SUM(CASE WHEN t.status_tempahan_adminKemudahan = 4 THEN 1 ELSE 0 END) AS total_gagal,
        SUM(CASE 
                WHEN t.status_tempahan_adminKemudahan = 3 
                     AND t.status_tempahan_pelulus = 2 
                     AND t.status_pembayaran = 1 
                THEN 1 
                ELSE 0 
            END) AS lulus_tidak_berbayar,
        SUM(CASE 
                WHEN t.status_tempahan_adminKemudahan = 5 
                     AND t.status_tempahan_pelulus = 2 
                     AND t.status_pembayaran = 3 
                THEN 1 
                ELSE 0 
            END) AS lulus_berbayar
    FROM tempah_asrama t
    JOIN asrama a ON t.id_asrama = a.id
    WHERE YEAR(t.tarikh_masuk) = :year
      AND MONTH(t.tarikh_masuk) = :month
      AND t.status_tempahan_adminKemudahan IS NOT NULL
      AND t.status_tempahan_adminKemudahan NOT IN (0, 1, 2)
      AND t.status_tempahan_pelulus IS NOT NULL
      AND t.status_tempahan_pelulus NOT IN (0, 1)
      AND a.blok = 'R'  -- Tambah klausa ini untuk memilih blok 'R'
";

$command_summary = Yii::$app->db->createCommand($summary_sql);
$command_summary->bindValue(':year', $selectedYear);
$command_summary->bindValue(':month', $selectedMonth);
$summary = $command_summary->queryOne();
?>

<?php $form = ActiveForm::begin([
    'method' => 'get',
    'action' => Url::to(['laporan/laporan-penghuni-asrama']),
]); ?>

<!-- Printable Header -->
<div class="header text-center d-none d-print-block">
    <img src="<?= Url::to('@web/images/LogoCiast.png', true) ?>" alt="Logo">
    <h4>LAPORAN PENGHUNI ASRAMA</h4>
    <h4>CIAST SHAH ALAM</h4>
    <p><?= date('F Y', strtotime("$selectedYear-$selectedMonth-01")) ?></p>
</div>

<div class="card1 shadow rounded border-0 p-2 mb-4">
        <div class="card-body d-flex flex-wrap gap-3 align-items-center justify-content-center">

        <div class="fw-bold">Pilih Bulan:</div>
        <select name="month" id="month" class="form-select form-select-sm" style="width: 110px;" onchange="this.form.submit()">
            <?php for ($m = 1; $m <= 12; $m++): ?>
                <option value="<?= $m ?>" <?= ($selectedMonth == $m) ? 'selected' : '' ?>>
                    <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
                </option>
            <?php endfor; ?>
        </select>

            <div class="fw-bold">Laporan Penghuni Asrama bagi Tahun:</div>
            <select name="year" id="year" class="form-select form-select-sm" style="width: 100px;" onchange="this.form.submit()">
            <?php for ($y = $currentYear; $y >= ($currentYear - 5); $y--): ?>
                <option value="<?= $y ?>" <?= ($selectedYear == $y) ? 'selected' : '' ?>>
                    <?= $y ?>
                </option>
            <?php endfor; ?>
            </select>
    </div>
</div>

<?php ActiveForm::end(); ?>


<div class="card shadow rounded border-0 p-4">
<div class="row mb-4">
    <?php foreach (['Jumlah Tempahan' => 'total_tempahan', 'Tempahan Lulus' => 'total_lulus', 'Tempahan Gagal' => 'total_gagal', 'Tidak Berbayar' => 'lulus_tidak_berbayar', 'Berbayar' => 'lulus_berbayar'] as $label => $key): ?>
        <div class="col-md-2">
            <div class="card text-white bg-<?= $key == 'total_gagal' ? 'danger' : ($key == 'total_lulus' ? 'success' : ($key == 'lulus_berbayar' ? 'info' : ($key == 'lulus_tidak_berbayar' ? 'warning' : 'primary'))) ?> mb-3 p-3">
                <h6 class="mb-0"><?= $label ?></h6>
                <h3><?= $summary[$key] ?? 0 ?></h3>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="row">
<?php foreach ($jenisBilikData as $jenis => $data): ?>
<div class="col-md-6 mb-4">
    <div class="card">
        <div class="card-header">
            <h5>Jenis Bilik -  <?= Html::encode($jenis) ?></h5>
        </div>
        <div class="card-body row">
            <div class="col-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Jumlah Tempahan</th>
                            <th>Lulus</th>
                            <th>Gagal</th>
                            <th>Berbayar</th>
                            <th>Tidak Berbayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?= $data['total_tempahan'] ?? 0 ?></td>
                            <td><?= $data['total_lulus'] ?? 0 ?></td>
                            <td><?= $data['total_gagal'] ?? 0 ?></td>
                            <td><?= $data['lulus_berbayar'] ?? 0 ?></td>
                            <td><?= $data['lulus_tidak_berbayar'] ?? 0 ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
              <div class="col-md-6 d-flex justify-content-center">
                <canvas id="chart-jenis-<?= md5($jenis) ?>"></canvas>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
</div>
</div>

<!-- <div class="mt-3" style="text-align: left;">
    <button onclick="window.print()" class="btn btn-primary btn-lg">
        <i class="fas fa-print"></i> Print
    </button>
</div> -->

<?php
$chartData = [];
foreach ($jenisBilikData as $jenis => $data) {
    $chartData[md5($jenis)] = [
        'label' => $jenis,
        'lulus_berbayar' => $data['lulus_berbayar'] ?? 0,
        'lulus_tidak_berbayar' => $data['lulus_tidak_berbayar'] ?? 0,
        'total_gagal' => $data['total_gagal'] ?? 0,
    ];
}
?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chartData = <?= json_encode($chartData) ?>;
        Object.keys(chartData).forEach(function(id) {
            const el = document.getElementById('chart-jenis-' + id);
            if (!el) return;

            const ctx = el.getContext('2d');
            const data = chartData[id];

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Lulus Berbayar', 'Lulus Tidak Berbayar', 'Gagal'],
                    datasets: [{
                        data: [data.lulus_berbayar, data.lulus_tidak_berbayar, data.total_gagal],
                        backgroundColor: ['#2196F3', '#FFC107', '#F44336'],
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                usePointStyle: true,
                                padding: 12
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function (tooltipItem) {
                                    const total = data.lulus_berbayar + data.lulus_tidak_berbayar + data.total_gagal;
                                    const val = tooltipItem.raw;
                                    const percentage = total ? ((val / total) * 100).toFixed(1) : 0;
                                    return tooltipItem.label + ': ' + val + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        });
    });
</script>



<?php
$this->registerCss('
h5 {
        font-size: 1rem; /* Adjust size */
        font-weight: bold; /* Make it bold */
        color:rgb(0, 0, 0); /* Text color */
        
    }
.card1 {
         background-color:rgb(177, 199, 222);
        border: 1px solidrgb(13, 19, 14); /* Warna border */
        height: 50px;
    }
    //     .fw-bold {
    //     color: white !important;
    // }
    @media print {
        .main-header, .main-footer, .sidebar, .navbar, .pagination, 
        button, .btn, .mt-3, footer { display: none !important; }

        .header {
            display: block !important;
            text-align: center;
            margin-bottom: 10px !important;
        }

        .header img { height: 80px; margin-bottom: 5px; }
        .header h4, .header p { margin: 5px 0; font-size: 18px; }

        table { page-break-inside: avoid; }
        label[for="year"], #year { display: none !important; }
    }

    table th {
        background-color: rgb(241, 241, 241) !important;
        font-weight: bold;
        text-align: center;
        // border-color: rgb(0, 0, 0) !important;
        color: rgb(241, 239, 239);

    }

    table td {
        //   background-color: rgb(0, 0, 0) !important;
        // font-weight: bold;
        text-align: center;
        //  border-color: rgb(0, 0, 0) !important;
    }

//     table {
//      border-color: rgb(0, 0, 0) !important;
// }



    canvas[id^="chart-blok"] {
        width: 300px !important;
        height: 300px !important;
        display: block;
        margin: 0 auto;
    }


    .chart-container {
        display: flex;
        justify-content: center;
        align-items: flex-start;
    }

    // canvas {
    //     flex: 1;
    //     max-width: 300px;
    // }

    .card-header {
    background-color: rgb(241, 241, 241) !important;
    }

');
?>

<script>
document.getElementById("year").addEventListener("change", function () {
    const selectedYear = this.value;
    window.location.href = "<?= Url::to(['laporan/laporan-penghuni-asrama']) ?>?year=" + selectedYear;
});
</script>
