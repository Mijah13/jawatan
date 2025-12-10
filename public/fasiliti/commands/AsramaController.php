<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use app\models\TempahAsrama;
use app\models\PelajarAsrama;
use app\models\Asrama;
use app\models\AsramaStatusLog;

class AsramaController extends Controller
{
    /**
     * Cronjob: Kemaskini status bilik asrama mengikut tarikh hari ini.
     * 
     * Command: php yii asrama/kemaskini-status
     */

// public function actionKemaskiniStatus()
// {
//     $today = date('Y-m-d');
//     Yii::info("🚀 Mula kemaskini status asrama untuk tarikh: $today", 'asrama-cron');

//     $asramaList = Asrama::find()->all();

//     foreach ($asramaList as $asrama) {
//         $asalStatus = $asrama->status_asrama;
//         $baruStatus = null;

//         // 🔎 Check kalau hari ini adalah dalam mana-mana tempahan aktif (masuk hingga keluar)
//         $isPenyewaIn = TempahAsrama::find()
//             ->where(['id_asrama' => $asrama->id]) // ada bilik
//             ->andWhere(['status_tempahan_pelulus' => 2]) //status lulus
//             ->andWhere(['<=', 'tarikh_masuk', $today]) 
//             ->andWhere(['>=', 'tarikh_keluar', $today])
//             ->exists();

//         $isStudentIn = PelajarAsrama::find()
//             ->where(['id_asrama' => $asrama->id])
//             ->andWhere(['<=', 'tarikh_masuk', $today])
//             ->andWhere(['>=', 'tarikh_keluar', $today])
//             ->exists();

//         if ($isPenyewaIn || $isStudentIn) {
//             $baruStatus = 6; // Diisi
//         } else {
//             // 🔄 Kalau dah lepas tempoh keluar + pembersihan
//             $latestKeluar = TempahAsrama::find()
//                 ->where(['id_asrama' => $asrama->id])
//                 ->andWhere(['status_tempahan_pelulus' => 2])
//                 ->andWhere(['<', 'tarikh_keluar', $today])
//                 ->orderBy(['tarikh_keluar' => SORT_DESC])
//                 ->one();

//             if ($latestKeluar && $latestKeluar->tarikh_pembersihan) {
//                 if ($today <= $latestKeluar->tarikh_pembersihan) {
//                     $baruStatus = 1; // Sedang dibersihkan
//                 } else {
//                     // Check tiada tempahan masa depan
//                     $futureBooking = TempahAsrama::find()
//                         ->where(['id_asrama' => $asrama->id])
//                         ->andWhere(['status_tempahan_pelulus' => 2])
//                         ->andWhere(['>', 'tarikh_masuk', $today])
//                         ->exists();

//                     if (!$futureBooking) {
//                         $baruStatus = 0; // Kosong
//                     }
//                 }
//             }
//         }

//         // ✅ Kemas kini hanya kalau status berubah
//         if ($baruStatus !== null && $asalStatus != $baruStatus) {
//             $asrama->status_asrama = $baruStatus;
//             $asrama->save(false);

//             // 🔁 Tutup log lama
//             $logSebelum = AsramaStatusLog::find()
//                 ->where(['id_asrama' => $asrama->id, 'tarikh_tamat' => null])
//                 ->orderBy(['tarikh_mula' => SORT_DESC])
//                 ->one();

//             if ($logSebelum) {
//                 // $logSebelum->tarikh_tamat = $today;
//                 $logSebelum->tarikh_tamat = date('Y-m-d', strtotime($today . ' -1 day'));
//                 $logSebelum->save(false);
//             }

//             // 🆕 Log baru
//             $logBaru = new AsramaStatusLog();
//             $logBaru->id_asrama = $asrama->id;
//             $logBaru->status_log = $baruStatus;
//             $logBaru->tarikh_mula = $today;
//             $logBaru->tarikh_tamat = null;
//             $logBaru->save(false);

//             Yii::info("Asrama ID {$asrama->id} => $asalStatus → $baruStatus | Log dikemaskini", 'asrama-cron');
//         }
//     }

//     echo "✅ Kemaskini status bilik siap untuk tarikh $today.\nAsrama ID {$asrama->id} => $asalStatus → $baruStatus | Log dikemaskini\n";
//     return ExitCode::OK;
// }

public function actionKemaskiniStatus()
{
    $today = date('Y-m-d');
    Yii::info("🚀 Mula kemaskini status asrama untuk tarikh: $today", 'asrama-cron');

    $asramaList = Asrama::find()->all();

    foreach ($asramaList as $asrama) {
        $asalStatus = $asrama->status_asrama;
        $baruStatus = null;

        // 🔎 Check kalau hari ini adalah dalam mana-mana tempahan aktif (masuk hingga keluar)
        $isPenyewaIn = TempahAsrama::find()
            ->where(['id_asrama' => $asrama->id])
            ->andWhere(['status_tempahan_pelulus' => 2])
            ->andWhere(['<=', 'tarikh_masuk', $today])
            ->andWhere(['>=', 'tarikh_keluar', $today])
            ->exists();

        $isStudentIn = PelajarAsrama::find()
            ->where(['id_asrama' => $asrama->id])
            ->andWhere(['<=', 'tarikh_masuk', $today])
            ->andWhere(['>=', 'tarikh_keluar', $today])
            ->exists();

        if ($isPenyewaIn || $isStudentIn) {
            $baruStatus = 6; // Diisi
        } else {
            // 🔄 Kalau dah lepas tempoh keluar + pembersihan
            $latestKeluar = TempahAsrama::find()
                ->where(['id_asrama' => $asrama->id])
                ->andWhere(['status_tempahan_pelulus' => 2])
                ->andWhere(['<', 'tarikh_keluar', $today])
                ->orderBy(['tarikh_keluar' => SORT_DESC])
                ->one();

            if ($latestKeluar && $latestKeluar->tarikh_pembersihan) {
                if ($today <= $latestKeluar->tarikh_pembersihan) {
                    $baruStatus = 1; // Sedang dibersihkan
                } else {
                    // Check tiada tempahan masa depan
                    $futureBooking = TempahAsrama::find()
                        ->where(['id_asrama' => $asrama->id])
                        ->andWhere(['status_tempahan_pelulus' => 2])
                        ->andWhere(['>', 'tarikh_masuk', $today])
                        ->exists();

                    if (!$futureBooking) {
                        $baruStatus = 0; // Kosong
                    }
                }
            }
        }

        // ✅ Kemas kini hanya kalau status berubah
        if ($baruStatus !== null && $asalStatus != $baruStatus) {
            $asrama->status_asrama = $baruStatus;
            $asrama->save(false);

            // 🔁 Tutup log lama
            $logSebelum = AsramaStatusLog::find()
                ->where(['id_asrama' => $asrama->id, 'tarikh_tamat' => null])
                ->orderBy(['tarikh_mula' => SORT_DESC])
                ->one();

            if ($logSebelum) {
                $logSebelum->tarikh_tamat = $today;
                $logSebelum->save(false);
            }

            // 🆕 Log baru
            $logBaru = new AsramaStatusLog();
            $logBaru->id_asrama = $asrama->id;
            $logBaru->status_log = $baruStatus;
            $logBaru->tarikh_mula = $today;
            $logBaru->tarikh_tamat = null;
            $logBaru->save(false);

            Yii::info("Asrama ID {$asrama->id} => $asalStatus → $baruStatus | Log dikemaskini", 'asrama-cron');
        }
    }

    echo "✅ Kemaskini status bilik siap untuk tarikh $today.\nAsrama ID {$asrama->id} => $asalStatus → $baruStatus | Log dikemaskini\n";
    return ExitCode::OK;
}




}
