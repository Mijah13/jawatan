<?php
use yii\helpers\ArrayHelper;
use app\models\Fasiliti;

// Fetch all jenis_bilik dynamically into an associative array
$jenisFasilitiMapping = ArrayHelper::map(Fasiliti::find()->all(), 'id', 'nama_fasiliti');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borang Tempahan Fasiliti</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            width: 210mm;
            height: 297mm;
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
            font-size: 18px;
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
        }
    </style>
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</head>
<body>
    <div class="print-container">
        <div class="header">
            <img src="<?= Yii::getAlias('@web/images/LogoCiast.png') ?>" alt="CIAST Logo">
            <h3>BORANG TEMPAHAN FASILITI</h3>
            <h3>CIAST SHAH ALAM</h3>
        </div>

        <?php if (!empty($approvedBookings)): ?>
            <?php $totalJumlah = 0; ?>
            <?php 
            $sessionLabels = [
                'sesiPagi' => 'Sesi pagi : 9am - 12pm',
                'sesiPetang' => 'Sesi petang : 2pm - 5pm',
                'sesiMalam' => 'Sesi malam : 8pm - 11pm',
                'sesiPagiPetang' => 'Sesi Pagi - Petang',
                'satuHari' => 'Satu Hari',
            ];
            ?>
            <?php foreach ($approvedBookings as $booking): ?>
                <?php
                $fasiliti = $jenisFasilitiMapping[$booking->fasiliti->id] ?? 'Kategori Tidak Diketahui';
                $rate = 0;
                $durations = [
                    'sesiPagi' => 3,
                    'sesiPetang' => 3,
                    'sesiMalam' => 3,
                    'sesiPagiPetang' => 6,
                    'satuHari' => 1,
                ];
                
                if ($booking->tempoh === 'satuHari') {
                    $date1 = new DateTime($booking->tarikh_masuk);
                    $date2 = new DateTime($booking->tarikh_keluar);
                    $days = $date1->diff($date2)->days;
                    $tempoh = $days > 0 ? $days : 1;
                } else {
                    $tempoh = $durations[$booking->tempoh] ?? 0;
                }
                
                switch ($booking->tempoh) {
                    case 'sesiPagi':
                    case 'sesiPetang':
                    case 'sesiPagiPetang':
                        $rate = $booking->fasiliti->kadar_sewa_perJamSiang ?? 0;
                        break;
                    case 'sesiMalam':
                        $rate = $booking->fasiliti->kadar_sewa_perJamMalam ?? 0;
                        break;
                    case 'satuHari':
                        $rate = $booking->fasiliti->kadar_sewa_perHari ?? 0;
                        break;
                }
                
                $kadarXTempoh = number_format($rate, 2) . " X " . $tempoh . ( $booking->tempoh === 'satuHari' ? " hari" : " jam") . " (" . ($sessionLabels[$booking->tempoh] ?? "Tidak Diketahui") . ")";
                $jumlah = $rate * $tempoh;
                $totalJumlah += $jumlah;
                ?>
                <table>
                    <tr>
                        <th colspan="2">MAKLUMAT TEMPAHAN</th>
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
                    <tr>
                        <th>Tarikh Masuk</th>
                        <td><?= Yii::$app->formatter->asDate($booking->tarikh_masuk, 'php:d/m/Y') ?></td>
                    </tr>
                    <tr>
                        <th>Tarikh Keluar</th>
                        <td><?= Yii::$app->formatter->asDate($booking->tarikh_keluar, 'php:d/m/Y') ?></td>
                    </tr>
                    <tr>
                        <th>Jenis Fasiliti</th>
                        <td><?= htmlspecialchars($fasiliti) ?></td>
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
                    <td style="text-align: right; font-weight: bold;"> <?= number_format($totalJumlah, 2) ?></td>
                </tr>
            </table>
        <?php else: ?>
            <p>Tiada Tempahan Diluluskan!</p>
        <?php endif; ?>
    </div>
</body>
</html>
