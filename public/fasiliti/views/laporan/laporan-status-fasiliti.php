<?php
/** @var $data array */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'MyFasiliti';
$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js', ['depends' => [\yii\web\JqueryAsset::class, \yii\web\YiiAsset::class]]);

$currentYear = date('Y');
$selectedYear = Yii::$app->request->get('year', $currentYear);



$status_sql = "
    SELECT fasiliti_status, COUNT(id) AS total_status
    FROM fasiliti
    GROUP BY fasiliti_status
";

$command_status = Yii::$app->db->createCommand($status_sql);
$status_result = $command_status->queryAll();


// Memetakan integer status ke dalam teks
$statusMapping = [
    0 => 'Kosong',
    2 => 'Rosak',
    4 => 'Diisi',
];


// Dapatkan jumlah hari digunakan per blok dan bulan
$penggunaan_sql = "
WITH RECURSIVE calendar AS (
    SELECT DATE(CONCAT(:tahun, '-01-01')) AS tarikh
    UNION ALL
    SELECT DATE_ADD(tarikh, INTERVAL 1 DAY)
    FROM calendar
    WHERE tarikh < DATE(CONCAT(:tahun, '-12-31'))
),
latest_status AS (
    SELECT 
        c.tarikh,
        f.id AS fasiliti_id,
        f.nama_fasiliti,
        (
            SELECT asl.fasiliti_status
            FROM fasiliti_status_log asl
            WHERE asl.fasiliti_id = f.id
              AND c.tarikh BETWEEN asl.tarikh_mula AND COALESCE(asl.tarikh_tamat, CURDATE())
            ORDER BY asl.tarikh_mula DESC, asl.id DESC
            LIMIT 1
        ) AS fasiliti_status
    FROM calendar c
    CROSS JOIN fasiliti f
)
SELECT
    nama_fasiliti,
    fasiliti_status,
    MONTH(tarikh) AS bulan,
    COUNT(*) AS jumlah_hari
FROM latest_status
WHERE YEAR(tarikh) = :tahun
  AND fasiliti_status IS NOT NULL
GROUP BY nama_fasiliti, fasiliti_status, MONTH(tarikh)
ORDER BY nama_fasiliti, bulan;

";

$command = Yii::$app->db->createCommand($penggunaan_sql);
$command->bindValue(':tahun', $selectedYear);
$data = $command->queryAll();


$statusPerBulan = [];

foreach ($data as $row) {
    $namaFasiliti = $row['nama_fasiliti'];
    $bulan = (int) $row['bulan'];
    $statusLabel = $statusMapping[$row['fasiliti_status']] ?? 'Lain';

    if (!isset($statusPerBulan[$namaFasiliti])) {
        $statusPerBulan[$namaFasiliti] = [];
    }

    if (!isset($statusPerBulan[$namaFasiliti][$statusLabel])) {
        $statusPerBulan[$namaFasiliti][$statusLabel] = array_fill(1, 12, 0);
    }

    $statusPerBulan[$namaFasiliti][$statusLabel][$bulan] = (int) $row['jumlah_hari'];
}


$pieData = [];
foreach ($statusPerBulan as $namaFasiliti => $statusList) {
    foreach ($statusMapping as $code => $statusLabel) {
        $pieData[$namaFasiliti][$statusLabel] = array_sum($statusList[$statusLabel] ?? []);
    }
}

?>

<!-- <h1 class="mb-4"></h1> -->

<!-- Year Selection Form -->
<?php $form = ActiveForm::begin([
    'method' => 'get',
    'action' => Url::to(['laporan/laporan-status-fasiliti']),
]); ?>

<!-- Printable Header -->
<div class="header d-none d-print-block">
    <div class="header-flex d-flex align-items-center justify-content-center text-center">
        <img src="<?= Url::to('@web/images/LogoCiast.png', true) ?>" alt="Logo CIAST">
        <div class="header-text ms-3">
            <h4 class="mb-1">LAPORAN PENGHUNI ASRAMA</h4>
            <h4 class="mb-1">CIAST SHAH ALAM</h4>
            <h5 class="fw-normal"><?= $selectedYear ?></h5>
        </div>
    </div>
</div>
<!-- Pilihan Tahun -->
<div class="form-group">
    <?= Html::beginForm(['laporan/laporan-tempahan-tahunan'], 'get') ?>
    <div class="card1 shadow rounded border-0 p-2 mb-4">
        <div class="card-body d-flex flex-wrap gap-3 align-items-center justify-content-center">
            <div class="fw-bold">Laporan Status Fasiliti bagi Tahun:</div>
            <select name="year" id="year" class="form-select form-select-sm" style="width: 100px;" onchange="this.form.submit()">
            <?php for ($y = $currentYear; $y >= ($currentYear - 5); $y--): ?>
                <option value="<?= $y ?>" <?= ($selectedYear == $y) ? 'selected' : '' ?>>
                    <?= $y ?>
                </option>
            <?php endfor; ?>
            </select>
            <div class="mt-6" style="text-align: left; margin-end: 10px;">
                <button onclick="window.print()" class="btn btn-primary btn-lg">
                    <i class="fas fa-print"></i>
                </button>
            </div>
        </div>
    <?= Html::endForm() ?>
</div>


<?php ActiveForm::end(); ?>
    <div class="card shadow rounded border-0 p-2 mb-4">
    <div class="print-content">
    <?php foreach ($statusPerBulan as $namaFasiliti => $statusData): ?>
    <div class="row mb-5">
        <div class="col-md-8">
            <p><strong><?= Html::encode($namaFasiliti) ?></strong></p>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="status-header">Status</th>
                        <?php for ($month = 1; $month <= 12; $month++): ?>
                            <th class="bulan"><?= DateTime::createFromFormat('!m', $month)->format('M') ?></th>
                        <?php endfor; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($statusMapping as $code => $statusLabel): ?>
                        <tr>
                            <td class="td-status<?= $statusLabel ?>"><?= $statusLabel ?></td>
                            <?php for ($month = 1; $month <= 12; $month++): ?>
                                <td>
                                    <?= $statusData[$statusLabel][$month] ?? 0 ?>
                                </td>
                            <?php endfor; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="col-md-4 chart-container">
            <?php $idFasiliti = preg_replace('/[^a-zA-Z0-9]/', '', $namaFasiliti); ?>
                <canvas id="pieChart_<?= $idFasiliti ?>"></canvas>

        </div>
    </div>
<?php endforeach; ?>
</div>
</div>
<?php
$pieJson = json_encode($pieData, JSON_NUMERIC_CHECK);

$js = <<<JS
    const pieData = $pieJson;
    const colors = {
        "Kosong": "#4CAF50",
        "Rosak": "#F44336",
        "Diisi": "#2196F3"
    };

    Object.keys(pieData).forEach(namaFasiliti => {
        const idFasiliti = namaFasiliti.replace(/[^a-zA-Z0-9]/g, '');
        const ctx = document.getElementById("pieChart_" + idFasiliti);
        if (ctx) {
            const data = pieData[namaFasiliti];
            const labels = Object.keys(data);
            const values = Object.values(data);

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: labels.map(label => colors[label] || "#9E9E9E"),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        title: {
                            display: true,
                            text: 'Status Keseluruhan - ' + namaFasiliti
                        }
                    },
                    cutout: '50%' // Donut style
                }
            });
        }
    });
JS;

$this->registerJs($js, \yii\web\View::POS_READY);
?>


<?php
$this->registerCss('
    h1 {
        font-size: 2.5rem;
        font-weight: bold;
        color: #ffffff;
        background: linear-gradient(90deg, #4CAF50, #2196F3);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-align: center;
        margin-bottom: 20px;
        text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
        letter-spacing: 1px;
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

    button, .btn, .mt-3 {
        font-size: 14px !important;
        padding: 6px 12px !important;
        width: auto !important;
    }

    .card p {
        font-size: 16px;
        font-weight: bold;
        color: #333;
    }

     .blok-card {
        border-bottom: 2px solid #ddd; /* Garisan pemisah antara blok-card dan table */
        padding-bottom: 15px; /* Jarak sedikit di bawah untuk ruang */
        margin-bottom: 20px; /* Jarak lebih antara card dan table */
    }

    th.bulan, th.status-header {
        background-color: #e0e0e0; /* warna neutral untuk header */
        font-weight: bold;
        text-align: center;
    }

    .td-status-Kosong {
        background-color: #2196F3;
        color: white;
        font-weight: bold;
    }

    .td-status-Rosak {
        background-color: #F44336;
        color: white;
        font-weight: bold;
    }

    .td-status-Diisi {
        background-color: #4CAF50;
        color: white;
        font-weight: bold;
    }

   @media print {
    /* Sembunyi elemen-elemen yang tak perlu */
    .main-header, .main-footer, .sidebar, .navbar, .pagination,
    button, .btn, .mt-3, footer, 
    label[for="year"], #year, 
    .chart-container {
        display: none !important;
    }

     .print-content {
        margin-top: 20px !important;
    }


    /* Hide the year dropdown and its label */
    select#year,
    label[for="year"],
    .card-body .fw-bold,
    .card-body select {
        display: none !important;
    }

    /* Optional: kalau kau nak buang spacing lebihan */
    .card-body {
        padding: 0 !important;
        margin: 0 !important;
    }

    body, .content-wrapper, .content, .container-fluid, .card {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        box-shadow: none !important;
        border: none !important;
        background: transparent !important;
    }

     /* Kemaskan header */
    .header {
        display: flex !important;
        align-items: center;
        justify-content: center;
        gap: 15px;
        margin-bottom: 5px !important;
    }

    .header img {
        height: 60px !important;   /* kecilkan sikit */
        margin-bottom: 10px;      /* naikkan atas */
    }

    .header h4, .header h5 {
        margin: 0 !important;
        line-height: 1.2;
        font-size: 16px !important;
        font-weight: bold;
    }

    /* Lepas header sekali je */
    .header + * {
        margin-top: 20px !important;
    }

    /* Selepas setiap page break (untuk header baru) */
    .page-break + .header {
        margin-top: 80px !important;
    }

    /* Atau kalau ada container selepas break */
    .page-break + * {
        margin-top: 80px !important;
    }

    /* Optional: force top-level container start at 0 */
    .container, .container-fluid, main, .content-wrapper {
        padding-top: 0 !important;
        margin-top: 0 !important;
    }

    canvas,
    .chart-container,
    .chart-wrapper,
    .chart-area {
        display: none !important;
    }

    /* Gaya table */
    table {
        width: 100% !important;
        border-collapse: collapse !important;
        page-break-inside: avoid;
        font-size: 14px !important;
    }

    table th, table td {
        border: 1px solid #000;
        padding: 8px !important;
        font-size: 13px !important;
        text-align: left;
        vertical-align: top;
    }

    /* Struktur kolum supaya tak lari */
    .row {
        display: flex !important;
        flex-wrap: wrap !important;
        margin: 0 !important;
    }

    .col-md-8, .col-md-4, .col-sm-6, .col-12 {
        width: 100% !important;
        // float: none !important;
        // padding: 0 !important;
        display: block !important;
    }

    /* Elak border/card yang tak perlu */
    .card, .card-body {
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
    }

    /* Optional: Page break */
    .page-break {
        page-break-after: always;
    }

    
');

$this->registerCss('
    canvas[id^="pieChart_"] {
        max-width: 190px !important;
        max-height: 190px !important;
        width: 100%;
        height: auto;
        display: block;
        margin: 0 auto;
    }

    .chart-container {
        text-align: center;
        padding-top: 10px;
        padding-bottom: 20px;
    }

');

?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const yearSelect = document.getElementById("year");

    function reloadPage() {
        const selectedYear = yearSelect.value;
        window.location.href = "<?= Url::to(['laporan/laporan-status-fasiliti']) ?>?year=" + selectedYear;
    }

    yearSelect.addEventListener("change", reloadPage);
});
</script>
