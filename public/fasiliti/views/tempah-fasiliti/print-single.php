<?php
use yii\helpers\ArrayHelper;
use app\models\Fasiliti;
use app\models\User;

// Fetch all jenis_bilik dynamically into an associative array
$jenisFasilitiMapping = ArrayHelper::index(Fasiliti::find()->all(), 'id');
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
            font-size: 16px;
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
            <h3>BIL TEMPAHAN FASILITI</h3>
            <h3>CIAST SHAH ALAM</h3>
        </div>

        <?php if (!empty($approvedBooking)): ?>
            <?php $totalJumlah = 0; 
        
            $booking_info = [];
            foreach ($approvedBooking as $key => $booking) {
                $booking_info[$key] = $booking;
            }
            $disokongUser = !empty($booking_info['disokong_oleh']) ? User::findOne($booking_info['disokong_oleh']) : null;
            $dilulusUser = !empty($booking_info['diluluskan_oleh']) ? User::findOne($booking_info['diluluskan_oleh']) : null;    
            ?>
            <?php
            $fasiliti = app\models\Fasiliti::findOne($booking_info['fasiliti_id']);
            $namaFasiliti = $fasiliti ? $fasiliti->nama_fasiliti : 'N/A';

            $fasilitiId = $booking_info['fasiliti_id'] ?? null;

            $kadarSewa = isset($jenisFasilitiMapping[$fasilitiId]) ? $jenisFasilitiMapping[$fasilitiId]->kadar_sewa_perHari : 0;

            // $rate = 0;
            $durations = [
                1 => 3,
                2 => 3,
                3 => 3,
                4 => 6,
                5 => 1,
            ];

            $sessionLabels = [
                1 => 'Sesi pagi : 9am - 12pm',
                2 => 'Sesi petang : 2pm - 5pm',
                3 => 'Sesi malam : 8pm - 11pm',
                4 => 'Sesi Pagi - Petang',
                5 => 'Satu Hari',
            ];
                
            if ($booking_info['tempoh'] === 5) {
                $date1 = new DateTime($booking_info['tarikh_masuk']);
                $date2 = new DateTime($booking_info['tarikh_keluar']);
                $days = $date1->diff($date2)->days;
                $tempoh = $days > 0 ? $days : 1;
            } else {
                $tempoh = $durations[$booking_info['tempoh']] ?? 0;
            }
            
            switch ($booking_info['tempoh']) {
                case 1:
                    $kadarSewa = isset($jenisFasilitiMapping[$fasilitiId]->kadar_sewa_perJamSiang) && $jenisFasilitiMapping[$fasilitiId]->kadar_sewa_perJamSiang !== ""
                        ? $jenisFasilitiMapping[$fasilitiId]->kadar_sewa_perJamSiang
                        : ($jenisFasilitiMapping[$fasilitiId]->kadar_sewa_perJam ?? 0);
                    break;
                case 2:
                    $kadarSewa = isset($jenisFasilitiMapping[$fasilitiId]->kadar_sewa_perJamSiang) && $jenisFasilitiMapping[$fasilitiId]->kadar_sewa_perJamSiang !== ""
                        ? $jenisFasilitiMapping[$fasilitiId]->kadar_sewa_perJamSiang
                        : ($jenisFasilitiMapping[$fasilitiId]->kadar_sewa_perJam ?? 0);
                    break;
                case 4:
                    $kadarSewa = isset($jenisFasilitiMapping[$fasilitiId]->kadar_sewa_perJamSiang) && $jenisFasilitiMapping[$fasilitiId]->kadar_sewa_perJamSiang !== ""
                        ? $jenisFasilitiMapping[$fasilitiId]->kadar_sewa_perJamSiang
                        : ($jenisFasilitiMapping[$fasilitiId]->kadar_sewa_perJam ?? 0);
                    break;
                case 3:
                    $kadarSewa = isset($jenisFasilitiMapping[$fasilitiId]->kadar_sewa_perJamMalam) && $jenisFasilitiMapping[$fasilitiId]->kadar_sewa_perJamMalam !== ""
                        ? $jenisFasilitiMapping[$fasilitiId]->kadar_sewa_perJamMalam
                        : ($jenisFasilitiMapping[$fasilitiId]->kadar_sewa_perJam ?? 0);
                    break;
                case 5:
                    $kadarSewa = isset($jenisFasilitiMapping[$fasilitiId]->kadar_sewa_perHari) ? $jenisFasilitiMapping[$fasilitiId]->kadar_sewa_perHari : 0;
                    break;
                default:
                    $kadarSewa = 0;
            }
            

            // echo "<pre>";
            // print_r($jenisFasilitiMapping[$fasilitiId] ?? "Fasiliti tidak dijumpai");
            // echo "</pre>";
            // exit;
            

                
            $kadarXTempoh = number_format($kadarSewa, 2) . " X " . $tempoh . ( $booking_info['tempoh'] === 5 ? " hari" : " jam") . " (" . ($sessionLabels[$booking_info['tempoh']] ?? "Tidak Diketahui") . ")";
            $jumlah = $kadarSewa * $tempoh;
            $totalJumlah += $jumlah;
            ?>
            <table>
                <tr>
                    <th colspan="2">MAKLUMAT TEMPAHAN</th>
                </tr>
                <tr>
                <th>ID</th>
                    <td><?= htmlspecialchars($booking_info['id']) ?></td>
                </tr>
                <tr>
                    <th>Nama</th>
                    <td><?= htmlspecialchars(User::findOne($booking_info['user_id'])->nama) ?></td>
                </tr>
                <tr>
                    <th>Kad Pengenalan</th>
                    <td><?= htmlspecialchars($booking_info['no_kp_pemohon']) ?></td>
                </tr>
                <tr>
                    <th>No Telefon</th>
                    <td><?= htmlspecialchars($booking_info['no_tel']) ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?= htmlspecialchars(User::findOne($booking_info['user_id'])->email) ?></td>
                </tr>
                <?php if (!empty($booking_info['agensi_pemohon'])): ?>
                    <tr>
                        <th>Nama Agensi</th>
                        <td><?= htmlspecialchars($booking_info['agensi_pemohon']) ?></td>
                    </tr>
                <?php endif; ?>
                <?php if (!empty($booking_info['tujuan'])): ?>
                    <tr>
                        <th>Tujuan</th>
                        <td><?= htmlspecialchars($booking_info['tujuan']) ?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <th>Tarikh Masuk</th>
                    <td><?= Yii::$app->formatter->asDate($booking_info['tarikh_masuk'], 'php:d/m/Y') ?></td>
                </tr>
                <tr>
                    <th>Tarikh Keluar</th>
                    <td><?= Yii::$app->formatter->asDate($booking_info['tarikh_keluar'], 'php:d/m/Y') ?></td>
                </tr>
                <tr>
                    <th>Jenis Fasiliti</th>
                    <td><?= htmlspecialchars($namaFasiliti) ?></td>
                </tr>
                 <tr>
                <th>Disokong Oleh</th>
                    <td><?= $disokongUser ? htmlspecialchars($disokongUser->nama) : '-' ?></td>
                </tr>
                <tr>
                    <th>Diluluskan Oleh</th>
                    <td><?= $dilulusUser ? htmlspecialchars($dilulusUser->nama) : '-' ?></td>
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
        <?php //endforeach; ?>
        <table>
            <tr>
                <th colspan="2" style="text-align: right;">Jumlah Bayaran (RM):</th>
                    <td style="text-align: right; font-weight: bold;">
                        <?php if ($booking_info['status_tempahan_adminKemudahan'] == 3): ?>
                            <span style="text-decoration: line-through;"><?= number_format($totalJumlah, 2) ?></span>
                        <?php else: ?>
                            <?= number_format($totalJumlah, 2) ?>
                        <?php endif; ?>
                    </td>

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