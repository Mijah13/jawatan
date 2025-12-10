<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use app\models\TempahFasiliti;
use app\models\Fasiliti;
use app\models\FasilitiStatusLog;

class FasilitiController extends Controller
{
    /**
     * Cronjob: Kemaskini status fasiliti mengikut tarikh hari ini.
     * 
     * Command: php yii fasiliti/kemaskini-status
     */

    // public function actionKemaskiniStatus()
    // {
    //     $today = date('Y-m-d');
    //     Yii::info("🚀 Mula kemaskini status fasiliti untuk tarikh: $today", 'fasiliti-cron');

    //     $tempahan = TempahFasiliti::find()
    //         ->where(['status_tempahan_pelulus' => 2])
    //         ->all();

    //     foreach ($tempahan as $item) {
    //         $fasiliti = Fasiliti::findOne($item->fasiliti_id);
    //         if (!$fasiliti) continue;

    //         $asalStatus = $fasiliti->fasiliti_status;
    //         $baruStatus = null;

    //         if ($today >= $item->tarikh_masuk && $today <= $item->tarikh_keluar) {
    //             $baruStatus = 4; // Diisi
    //         } elseif ($today > $item->tarikh_keluar) {
    //             // Check jika fasiliti tidak digunakan lagi lepas tarikh keluar
    //             $masihDiguna = TempahFasiliti::find()
    //                 ->where(['fasiliti_id' => $fasiliti->id])
    //                 ->andWhere(['status_tempahan_pelulus' => 2])
    //                 ->andWhere(['>=', 'tarikh_masuk', $today])
    //                 ->exists();

    //             if (!$masihDiguna) {
    //                 $baruStatus = 0; // Kosong
    //             }
    //         }

    //         if ($baruStatus !== null && $asalStatus != $baruStatus) {
    //             $fasiliti->fasiliti_status = $baruStatus;
    //             $fasiliti->save(false);

    //             // Tutup log lama
    //             $logSebelum = FasilitiStatusLog::find()
    //                 ->where(['fasiliti_id' => $fasiliti->id])
    //                 ->andWhere(['tarikh_tamat' => null])
    //                 ->orderBy(['tarikh_mula' => SORT_DESC])
    //                 ->one();

    //             if ($logSebelum) {
    //                 // $logSebelum->tarikh_tamat = $today;
    //                 $logSebelum->tarikh_tamat = date('Y-m-d', strtotime($today . ' -1 day'));

    //                 $logSebelum->save(false);
    //             }

    //             // Insert log baru
    //             $logBaru = new FasilitiStatusLog();
    //             $logBaru->fasiliti_id = $fasiliti->id;
    //             $logBaru->status_log = $baruStatus;
    //             $logBaru->tarikh_mula = $today;
    //             $logBaru->tarikh_tamat = null;
    //             $logBaru->save(false);

    //             Yii::info("Fasiliti ID {$fasiliti->id} => $asalStatus → $baruStatus | Log dikemaskini", 'fasiliti-cron');
    //         }
    //     }

    //     echo "✅ Kemaskini status fasiliti siap untuk tarikh $today.\n";
    //     return ExitCode::OK;
    // }

    public function actionKemaskiniStatus()
    {
        $today = date('Y-m-d');
        Yii::info("🚀 Mula kemaskini status fasiliti untuk tarikh: $today", 'fasiliti-cron');

        $tempahan = TempahFasiliti::find()
            ->where(['status_tempahan_pelulus' => 2])
            ->all();

        foreach ($tempahan as $item) {
            $fasiliti = Fasiliti::findOne($item->fasiliti_id);
            if (!$fasiliti) continue;

            $asalStatus = $fasiliti->fasiliti_status;
            $baruStatus = null;

            if ($today >= $item->tarikh_masuk && $today <= $item->tarikh_keluar) {
                $baruStatus = 4; // Diisi
            } elseif ($today > $item->tarikh_keluar) {
                // Check jika fasiliti tidak digunakan lagi lepas tarikh keluar
                $masihDiguna = TempahFasiliti::find()
                    ->where(['fasiliti_id' => $fasiliti->id])
                    ->andWhere(['status_tempahan_pelulus' => 2])
                    ->andWhere(['>=', 'tarikh_masuk', $today])
                    ->exists();

                if (!$masihDiguna) {
                    $baruStatus = 0; // Kosong
                }
            }

            if ($baruStatus !== null && $asalStatus != $baruStatus) {
                $fasiliti->fasiliti_status = $baruStatus;
                $fasiliti->save(false);

                // Tutup log lama
                $logSebelum = FasilitiStatusLog::find()
                    ->where(['fasiliti_id' => $fasiliti->id])
                    ->andWhere(['tarikh_tamat' => null])
                    ->orderBy(['tarikh_mula' => SORT_DESC])
                    ->one();

                if ($logSebelum) {
                    $logSebelum->tarikh_tamat = $today;
                    $logSebelum->save(false);
                }

                // Insert log baru
                $logBaru = new FasilitiStatusLog();
                $logBaru->fasiliti_id = $fasiliti->id;
                $logBaru->status_log = $baruStatus;
                $logBaru->tarikh_mula = $today;
                $logBaru->tarikh_tamat = null;
                $logBaru->save(false);

                Yii::info("Fasiliti ID {$fasiliti->id} => $asalStatus → $baruStatus | Log dikemaskini", 'fasiliti-cron');
            }
        }

        echo "✅ Kemaskini status fasiliti siap untuk tarikh $today.\n";
        return ExitCode::OK;
    }
}
