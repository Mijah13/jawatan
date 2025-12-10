<?php
use yii\helpers\ArrayHelper;
use app\models\JenisAsrama;

// Fetch all jenis_bilik dynamically into an associative array
$jenisBilikMapping = ArrayHelper::map(JenisAsrama::find()->all(), 'id', 'jenis_bilik');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borang Tempahan Asrama</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            width: 210mm; /* A4 width */
            height: 297mm; /* A4 height */
            display: flex;
        }

        .print-container {
            margin: 0 auto;
            width: 80%;
            padding: 60px;
            text-align: left;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            height: 60px;
        }

        .header h3 {
            margin: 5px 0;
            font-size: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th, td {
            font-size: 11px;
        }

        table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            @page {
            margin: 0; /* Removes headers and footers entirely */
            margin-top: 20mm;
            margin-bottom: 20mm;
            }

            .print-container {
                width: 100%;
            }

            .header {
                page-break-inside: avoid;
            }

            table {
                page-break-inside: avoid;
            }
            p{
                font-size: 10px;
            }
            table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        }
    </style>
    <script>
        window.onload = function() {
            window.print();

            window.onafterprint = function() {
                window.close();
            };
        };
    </script>
</head>
<body>
    <div class="print-container">
        <div class="header">
            <img src="<?= Yii::getAlias('@web/images/LogoCiast.png') ?>" alt="CIAST Logo">
            <h3>BORANG TEMPAHAN ASRAMA</h3>
            <h3>CIAST SHAH ALAM</h3>
        </div>

        <?php if (!empty($approvedBooking)): ?>
    <?php 
    $totalJumlah = 0; // Initialize total amount variable
    // return print_r($approvedBooking);
    foreach ($approvedBooking as $booking): 
        return print_r($booking)
    ?>
        <?php
        // Use ArrayHelper mapping to get the room category name
        $kategoriBilik = $jenisBilikMapping[$booking->jenis_bilik] ?? 'Kategori Tidak Diketahui';
        $asrama = app\models\Asrama::findOne($booking->id_asrama);
        $idAsrama = $asrama ? $asrama->blok . $asrama->aras . $asrama->no_asrama : 'N/A';

        // Calculate Kadar X Tempoh and Jumlah
        $date1 = new DateTime($booking->tarikh_masuk);
        $date2 = new DateTime($booking->tarikh_keluar);
        $interval = $date1->diff($date2);
        $days = $interval->days; // Number of nights

        // Get room rental rate
        $kadarSewa = $booking->jenisBilik->kadar_sewa ?? 0;
        $kadarXTempoh = number_format($kadarSewa, 2) . " X " . $days;
        $jumlah = $kadarSewa * $days;

        // Add to total
        $totalJumlah += $jumlah;
        ?>
        <table>
            <tr>
                <th colspan="2">MAKLUMAT TEMPAHAN</th>
            </tr>
            <tr>
                <th>ID</th>
                <td><?= htmlspecialchars($booking->id) ?></td>
            </tr>
            <tr>
                <th>Nama</th>
                <td><?= htmlspecialchars($booking->user->nama) ?></td>
            </tr>
            <tr>
                <th>Kad Pengenalan</th>
                <td><?= htmlspecialchars($booking->no_kp_pemohon) ?></td>
            </tr>
            <tr>
                <th>No Telefon</th>
                <td><?= htmlspecialchars($booking->no_tel) ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?= htmlspecialchars($booking->user->email) ?></td>
            </tr>
            <?php if (!empty($booking->agensi_pemohon)): ?>
                <tr>
                    <th>Nama Agensi</th>
                    <td><?= htmlspecialchars($booking->agensi_pemohon) ?></td>
                </tr>
            <?php endif; ?>
            <?php if (!empty($booking->tujuan)): ?>
                <tr>
                    <th>Tujuan</th>
                    <td><?= htmlspecialchars($booking->tujuan) ?></td>
                </tr>
            <?php endif; ?>
            <tr>
                <th>Tarikh Masuk</th>
                <td><?= Yii::$app->formatter->asDate($booking->tarikh_masuk, 'php:d/m/Y') ?></td>
            </tr>
            <tr>
                <th>Tarikh Keluar</th>
                <td><?= Yii::$app->formatter->asDate($booking->tarikh_keluar, 'php:d/m/Y') ?></td>
            </tr>
            <tr>
                <th>Kategori Bilik</th>
                <td><?= htmlspecialchars($kategoriBilik) ?></td>
            </tr>
            <tr>
                <th>No Bilik</th>
                <td><?= htmlspecialchars($idAsrama) ?></td>
            </tr>
            <tr>
                <th>Kadar X Tempoh</th>
                <td><?= $kadarXTempoh ?></td>
            </tr>
            <tr>
                <th>Jumlah (RM)</th>
                <td><?= number_format($jumlah, 2) ?></td>
            </tr>
        </table>
    <?php endforeach; ?>
    <table>
        <tr>
            <th colspan="2" style="text-align: right;">Jumlah Bayaran (RM):</th>
            <td style="text-align: right; font-weight: bold;"><?= number_format($totalJumlah, 2) ?></td>
        </tr>
    </table>
    <br>
    <span>
        <p><strong> Nota : Borang tempahan ini adalah janaan komputer dan tidak perlu ditandatangani.</strong></p>
    </span>
<?php else: ?>
    <p>Tiada Tempahan Diluluskan!</p>
<?php endif; ?>

    </div>
</body>
</html>
