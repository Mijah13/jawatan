<?php
/** @var $data array */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'MyFasiliti';
$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js', ['depends' => [\yii\web\JqueryAsset::class]]);



$currentYear = date('Y');
$selectedYear = Yii::$app->request->get('year', $currentYear);

$sql = "
    SELECT a.blok, COUNT(*) AS total_tempahan
    FROM pelajar_asrama p
    JOIN asrama a ON p.id_asrama = a.id
    WHERE YEAR(p.tarikh_masuk) = :year AND a.blok IN ('K', 'M', 'N', 'R')
    GROUP BY a.blok

    UNION ALL

    SELECT a.blok, COUNT(*) AS total_tempahan
    FROM tempah_asrama t
    JOIN asrama a ON t.id_asrama = a.id
    WHERE YEAR(t.tarikh_masuk) = :year AND a.blok = 'R'
    GROUP BY a.blok


";

$command = Yii::$app->db->createCommand($sql);
$command->bindValue(':year', $selectedYear); // Ikat tahun yang dipilih ke parameter
$result = $command->queryAll(); // Dapatkan hasil
$blok = [];

foreach ($result as $row) {
    $blokName = $row['blok'];
    $total = $row['total_tempahan'];

    if (!isset($blok[$blokName])) {
        $blok[$blokName] = 0;
    }
    $blok[$blokName] += $total;
}

// SQL untuk dapatkan bilangan bilik bagi setiap blok
$bilik_sql = "
    SELECT a.blok, COUNT(b.id) AS total_bilik
    FROM asrama a
    LEFT JOIN asrama b ON a.id = b.id
    GROUP BY a.blok
";

$command = Yii::$app->db->createCommand($bilik_sql);
$result_bilik = $command->queryAll(); // Dapatkan hasil bilik
$blokBilik = [];

foreach ($result_bilik as $row) {
    $blokBilik[$row['blok']] = $row['total_bilik'];
}

$status_sql = "
    SELECT blok, status_asrama, COUNT(id) AS total_status
    FROM asrama
    GROUP BY blok, status_asrama
";

$command_status = Yii::$app->db->createCommand($status_sql);
$status_result = $command_status->queryAll();
$blokStatus = [];

// Memetakan integer status ke dalam teks
$statusMapping = [
    0 => 'Kosong',
    3 => 'Rosak',
    6 => 'Diisi',
];


foreach ($status_result as $status_row) {
    $status_code = $status_row['status_asrama'];
    
    if (isset($statusMapping[$status_code])) {
        $blokStatus[$status_row['blok']][$statusMapping[$status_code]] = $status_row['total_status'];
    }
}

// Dapatkan jumlah hari digunakan per blok dan bulan

$penggunaan_sql = <<<'SQL'
WITH RECURSIVE calendar AS (
  SELECT DATE(CONCAT(:tahun, '-01-01')) AS tarikh
  UNION ALL
  SELECT DATE_ADD(tarikh, INTERVAL 1 DAY)
  FROM calendar
  WHERE tarikh < LEAST(DATE(CONCAT(:tahun, '-12-31')), CURDATE())
),
status_harian AS (
  SELECT
    c.tarikh,
    a.blok,
    a.id AS id_asrama,
    (
      SELECT asl2.status_log
      FROM asrama_status_log AS asl2
      WHERE asl2.id_asrama = a.id
        AND c.tarikh BETWEEN asl2.tarikh_mula
                          AND COALESCE(asl2.tarikh_tamat, CURDATE())
      ORDER BY asl2.tarikh_mula DESC, asl2.id DESC
      LIMIT 1
    ) AS status_log
  FROM calendar AS c
  CROSS JOIN asrama AS a
  WHERE c.tarikh <= CURDATE()
)
SELECT
  blok,
  status_log,
  MONTH(tarikh) AS bulan,
  COUNT(*) AS jumlah_hari
FROM status_harian
WHERE status_log IS NOT NULL
GROUP BY blok, status_log, bulan
ORDER BY blok, bulan
SQL;

$command = Yii::$app->db->createCommand($penggunaan_sql);
$command->bindValue(':tahun', $selectedYear); // contoh: 2025
$data = $command->queryAll();





// $penggunaan_sql = "
// WITH RECURSIVE calendar AS (
//     SELECT DATE(CONCAT(:tahun, '-01-01')) AS tarikh
//     UNION ALL
//     SELECT DATE_ADD(tarikh, INTERVAL 1 DAY)
//     FROM calendar
//     WHERE tarikh < DATE(CONCAT(:tahun, '-12-31'))
// ),
// status_harian AS (
//     SELECT
//         c.tarikh,
//         a.blok,
//         a.id AS id_asrama,
//         (
//             SELECT asl2.status_log
//             FROM asrama_status_log asl2
//             WHERE asl2.id_asrama = a.id
//               AND c.tarikh BETWEEN asl2.tarikh_mula 
//                               AND COALESCE(asl2.tarikh_tamat, CURDATE())
//             ORDER BY asl2.tarikh_mula DESC, asl2.id DESC
//             LIMIT 1
//         ) AS status_log
//     FROM calendar c
//     JOIN asrama a45t
    
//       ON c.tarikh <= CURDATE()
// )
// SELECT
//     blok,
//     status_log,
//     MONTH(tarikh) AS bulan,
//     COUNT(*) AS jumlah_hari
// FROM status_harian
// WHERE status_log IS NOT NULL
// GROUP BY blok, status_log, bulan
// ORDER BY blok, bulan
// ";

// $command = Yii::$app->db->createCommand($penggunaan_sql);
// $command->bindValue(':tahun', $selectedYear);
// $data = $command->queryAll();


$statusPerBulan = [];

foreach ($data as $row) {
    $blokName = $row['blok'];
    $bulan = (int) $row['bulan'];
    $statusLabel = $statusMapping[$row['status_log']] ?? 'Lain';

    if (!isset($statusPerBulan[$blokName][$statusLabel])) {
        $statusPerBulan[$blokName][$statusLabel] = array_fill(1, 12, 0);
    }

    $statusPerBulan[$blokName][$statusLabel][$bulan] = (int) $row['jumlah_hari'];
}

$pieData = [];
foreach (['K', 'M', 'N', 'R'] as $blokName) {
    $pieData[$blokName] = [];
    foreach ($statusMapping as $code => $statusLabel) {
        $pieData[$blokName][$statusLabel] = array_sum($statusPerBulan[$blokName][$statusLabel] ?? []);
    }
}



?>

<!-- <h1 class="mb-4"></h1> -->

<!-- Year Selection Form -->
<?php $form = ActiveForm::begin([
    'method' => 'get',
    'action' => Url::to(['laporan/laporan-status-asrama']),
]); ?>

<!-- Printable Header -->
<div class="header text-center d-none d-print-block">
    <img src="<?= Url::to('@web/images/LogoCiast.png', true) ?>" alt="Logo">
    <h4>LAPORAN PENGHUNI ASRAMA</h4>
    <h4>CIAST SHAH ALAM</h4>
    <?= $selectedYear ?>
</div>

<div class="card1 shadow rounded border-0 p-2 mb-4">
        <div class="card-body d-flex flex-wrap gap-3 align-items-center justify-content-center">
            <div class="fw-bold">Laporan Status Asrama bagi Tahun:</div>
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

    <!-- <h3 class="mb-3">Laporan Penghuni Asrama - Tahun <?= $selectedYear ?></h3> -->

    <!-- Display total bookings per block -->
     <div class="card shadow-lg rounded border-0 p-4">
    <div class="row">
        <div class="col-md-3">
            <div class="card p-4 blok-card">
                <h5>Blok K</h5>
                <p>Jumlah Tempahan: <?= isset($blok['K']) ? $blok['K'] : 0 ?></p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-4 blok-card">
                <h5>Blok M</h5>
                <p>Jumlah Tempahan: <?= isset($blok['M']) ? $blok['M'] : 0 ?></p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-4 blok-card">
                <h5>Blok N</h5>
                <p>Jumlah Tempahan: <?= isset($blok['N']) ? $blok['N'] : 0 ?></p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-4 blok-card">
                <h5>Blok R</h5>
                <p>Jumlah Tempahan: <?= isset($blok['R']) ? $blok['R'] : 0 ?></p>
            </div>
        </div>
    </div>

     <!-- Nota formula pengiraan -->
<div class="alert alert-info mt-3" style="font-size: 0.9rem; background: #e8f4fd; border-left: 4px solid #2196F3;">
    <strong>Nota:</strong> Jumlah hari bagi setiap status dikira menggunakan formula:
    <em><br>Bilangan status dalam sebulan × Bilangan hari dalam sebulan</em>.
    <br>
    Ini bermaksud sistem mengira berapa banyak hari bilik berada dalam sesuatu status (contohnya
    <span class="text-success fw-bold">Diisi</span>, <span class="text-primary fw-bold">Kosong</span>, atau 
    <span class="text-danger fw-bold">Rosak</span>) sepanjang bulan tersebut.
</div>

    <?php foreach (['K', 'M', 'N', 'R'] as $blokName): ?>
    <div class="row mb-5">
        <div class="col-md-8">
            <p><strong>Blok <?= $blokName ?></strong> - Jumlah Bilik: <?= isset($blokBilik[$blokName]) ? $blokBilik[$blokName] : 0 ?></p>

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
                            <td class="td-status-<?= $statusLabel ?>"><?= $statusLabel ?></td>
                            <?php for ($month = 1; $month <= 12; $month++): ?>
                                <td>
                                    <?= isset($statusPerBulan[$blokName][$statusLabel][$month])
                                        ? $statusPerBulan[$blokName][$statusLabel][$month]
                                        : 0 ?>
                                </td>
                            <?php endfor; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>

        <div class="col-md-4 chart-container">
            <canvas id="pieChart_<?= $blokName ?>"></canvas>
            <div class="chart-legend" id="legend_<?= $blokName ?>"></div>
        </div>
    </div> 

<?php endforeach; ?>


    <!-- Print Button -->
    <!-- <div class="mt-3" style="text-align: left; margin-bottom: 10px;">
        <button onclick="window.print()" class="btn btn-primary btn-lg">
            <i class="fas fa-print"></i> Print
        </button>
    </div> -->
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const pieData = <?= json_encode($pieData) ?>;

    const colors = {
        "Kosong": "#2196F3", // Biru
        "Rosak": "#F44336",  // Merah
        "Diisi": "#4CAF50"   // Hijau
    };

    Object.keys(pieData).forEach(blok => {
        const ctx = document.getElementById("pieChart_" + blok);
        if (ctx) {
            const data = pieData[blok];
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
                            text: 'Status Keseluruhan - Blok ' + blok
                        }
                    },
                    cutout: '50%' // Donut style
                }
            });
        }
    });
});
</script>

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

    .card1 {
        // background-color:rgb(68, 68, 68); /* Warna latar belakang */
        background-color:rgb(177, 199, 222);
        border: 1px solidrgb(13, 19, 14); /* Warna border */
        height: 50px;
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



    // .row {
    //     display: flex;
    //     gap: 20px; /* beri ruang antara kolum */
    // }

    // .col-md-8 {
    //     flex-grow: 2; /* bagi lebih ruang untuk jadual */
    // }

    // .col-md-4 {
    //     flex-grow: 1; /* ruang lebih kecil untuk carta */
    // }

    // .table {
    //     width: 100%; /* pastikan jadual mengisi ruang penuh dalam kolum */
    //     table-layout: fixed; /* elakkan jadual menjadi terlalu besar atau kecil */
    // }


    

    // /* Hide unwanted elements when printing */
    // @media print {
    //     .main-header, .main-footer, .sidebar, .navbar, .pagination, 
    //     button, .btn, .mt-3, footer { 
    //         display: none !important; 
    //     }

    //     body, .content-wrapper, .content, .container-fluid, .card {
    //         margin: 0 !important;
    //         padding: 0 !important;
    //         width: 90% !important;
    //         max-width: none !important;
    //         box-shadow: none !important;
    //         border: none !important;
    //         background: transparent !important;
    //     }

    //     .header {
    //         display: block !important;
    //         text-align: center;
    //         margin-bottom: 10px !important;
    //     }

    //     .header img {
    //         height: 80px;
    //         margin-bottom: 5px;
    //     }

    //     .header h4, .header p {
    //         margin: 5px 0;
    //         font-size: 18px;
    //     }

    //     table {
    //             page-break-inside: avoid;
    //         }

    //     label[for="year"], #year {
    //         display: none !important;
    //     }

    // table {
    //     width: 100% !important;
    //     font-size: 14px !important;
    // }

    // table th, table td {
    //     padding: 8px !important;
    //     font-size: 13px !important;
    // }

    // .table {
    //     page-break-inside: avoid;
    //     border-collapse: collapse !important;
    // }

    // .row {
    //     display: flex !important;
    //     flex-direction: row !important;
    //     flex-wrap: nowrap !important;
    // }

    // .col-md-8, .col-md-4 {
    //     width: 100% !important;
    //     display: block !important;
    //     float: none !important;
    // }

    // .chart-container {
    //     display: none !important;
    // }


    // }

    
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
        window.location.href = "<?= Url::to(['laporan/laporan-status-asrama']) ?>?year=" + selectedYear;
    }

    yearSelect.addEventListener("change", reloadPage);
});
</script>
