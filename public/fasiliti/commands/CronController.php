<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\TempahFasiliti;
use app\models\TempahAsrama;
use yii\helpers\Html;

class CronController extends Controller
{
    public function actionCheckPayments()
    {
        echo "---- Checking Fasiliti Payments (New Logic) ----\n";

        $today = date('Y-m-d');
        $tempahanList = TempahFasiliti::find()
            ->where(['status_tempahan_adminKemudahan' => 2])
            ->andWhere(['IS NOT', 'tarikh_invois_dijana', null])
            ->all();

        echo "Total bookings with invois_dijana: " . count($tempahanList) . "\n";

        foreach ($tempahanList as $tempahan) {
            $tarikhInvois = date('Y-m-d', strtotime($tempahan->tarikh_invois_dijana));
            $hariLepasInvois = (strtotime($today) - strtotime($tarikhInvois)) / (60*60*24);

            echo "Booking ID {$tempahan->id}: Invois dijana $tarikhInvois ($hariLepasInvois hari lepas)\n";

            // Tiada slip langsung, baru kita proceed
            if (empty($tempahan->slip_pembayaran)) {

                if ($hariLepasInvois == 3) {
                    echo "Hantar peringatan hari ke-3 kepada user...\n";
                    $this->sendReminderEmailFasiliti($tempahan);

                } elseif ($hariLepasInvois > 5) {
                    echo "Auto-cancel tempahan ID {$tempahan->id} kerana tiada bayaran selepas 5 hari.\n";
                    $tempahan->status_tempahan_adminKemudahan = 4; // Batal
                    $tempahan->save();
                }

            } else {
                echo "Slip pembayaran telah dimuat naik. Tunggu semakan admin.\n";
            }
        }
    }

    // php /var/www/fasiliti/yii cron/check-asrama-payments 
    public function actionCheckAsramaPayments()
    {
        echo "---- Checking Fasiliti Payments (New Logic) ----\n";

        $today = date('Y-m-d');
        $tempahanList = TempahAsrama::find()
            ->where(['status_tempahan_adminKemudahan' => 2])
            ->andWhere(['IS NOT', 'tarikh_invois_dijana', null])
            ->all();

        echo "Total bookings with invois_dijana: " . count($tempahanList) . "\n";

        foreach ($tempahanList as $tempahan) {
            $tarikhInvois = date('Y-m-d', strtotime($tempahan->tarikh_invois_dijana));
            $hariLepasInvois = (strtotime($today) - strtotime($tarikhInvois)) / (60*60*24);

            echo "Booking ID {$tempahan->id}: Invois dijana $tarikhInvois ($hariLepasInvois hari lepas)\n";

            // Tiada slip langsung, baru kita proceed
            if (empty($tempahan->slip_pembayaran)) {

                if ($hariLepasInvois == 3) {
                    echo "Hantar peringatan hari ke-3 kepada user...\n";
                    $this->sendReminderEmailAsrama($tempahan);

                } elseif ($hariLepasInvois > 5) {
                    echo "Auto-cancel tempahan ID {$tempahan->id} kerana tiada bayaran selepas 5 hari.\n";
                    $tempahan->status_tempahan_adminKemudahan = 4; // Batal
                    $tempahan->save();
                }

            } else {
                echo "Slip pembayaran telah dimuat naik. Tunggu semakan admin.\n";
            }
        }
    }

    protected function sendReminderEmailFasiliti($tempahan)
    {
        $user = $tempahan->user;
        if (!$user) return;

        $jenisFasiliti = $tempahan->Fasiliti ? $tempahan->Fasiliti->nama_fasiliti : 'Tidak Dikenalpasti';
        $tarikhMasuk = date('d-m-Y', strtotime($tempahan->tarikh_masuk));

        Yii::$app->mailer2->compose()
            ->setTo($user->email)
            ->setFrom([Yii::$app->params['adminEmail'] => 'MyFasiliti'])
            ->setSubject('Peringatan Bayaran Tempahan Fasiliti')
            ->setHtmlBody("
                <p>Hai " . Html::encode($user->nama) . ",</p>
                <p>Tempahan anda untuk fasiliti <strong>" . Html::encode($jenisFasiliti) . "</strong> pada <strong>$tarikhMasuk</strong> masih belum dijelaskan.</p>
                <p>Sila jelaskan bayaran pada sistem IPayment dan <strong>muat naik slip pembayaran</strong> ke dalam sistem dengan kadar segera.</p>
                <p><strong>Tempahan anda akan dibatalkan secara automatik sekiranya bayaran tidak dibuat selepas hari ke-5 daripada tarikh invois dijana.</strong></p>
                <p>Terima kasih.</p>
            ")
            ->send();
    }
    
    protected function sendReminderEmailAsrama($tempahan)
    {
        $user = $tempahan->user;
        if (!$user) return;

        $jenisBilik = $tempahan->jenisBilik ? $tempahan->jenisBilik->jenis_bilik : 'Tidak Dikenalpasti';
        $tarikhMasuk = date('d-m-Y', strtotime($tempahan->tarikh_masuk));

        Yii::$app->mailer2->compose()
            ->setTo($user->email)
            ->setFrom([Yii::$app->params['adminEmail'] => 'MyFasiliti'])
            ->setSubject('Peringatan Bayaran Tempahan Asrama')
            ->setHtmlBody("
                <p>Hai " . Html::encode($user->nama) . ",</p>
                <p>Tempahan anda untuk asrama jenis <strong>" . Html::encode($jenisBilik) . "</strong> pada <strong>$tarikhMasuk</strong> masih belum dijelaskan.</p>
                <p>Sila jelaskan bayaran pada sistem IPayment dan <strong>muat naik slip pembayaran</strong> ke dalam sistem dengan kadar segera.</p>
                <p><strong>Tempahan anda akan dibatalkan secara automatik sekiranya bayaran tidak dibuat selepas hari ke-5 daripada tarikh invois dijana.</strong></p>
                <p>Terima kasih.</p>
            ")
            ->send();
    }


    public function actionReminderBelumHantarTempahan()
    {
        echo "---- Checking Saved But Not Submitted Tempahan (Fasiliti & Asrama) ----\n";

        $now = time();

        // 1. Tempahan Fasiliti belum dihantar
        $fasilitiList = \app\models\TempahFasiliti::find()
            ->where(['status_tempahan_adminKemudahan' => 0])
            ->all();

        foreach ($fasilitiList as $tempahan) {
            $createdAt = strtotime($tempahan->created_at);
            $jamLepas = ($now - $createdAt) / (60 * 60);

            if ($jamLepas > 5) {
                echo "Fasiliti Tempahan ID {$tempahan->id} belum dihantar selepas $jamLepas jam. Hantar reminder...\n";
                $this->sendBelumHantarReminderFasiliti($tempahan);
            }
        }

        // 2. Tempahan Asrama belum dihantar
        $asramaList = \app\models\TempahAsrama::find()
            ->where(['status_tempahan_adminKemudahan' => 0])
            ->all();

        foreach ($asramaList as $tempahan) {
            $createdAt = strtotime($tempahan->created_at);
            $jamLepas = ($now - $createdAt) / (60 * 60);

            if ($jamLepas > 5) {
                echo "Asrama Tempahan ID {$tempahan->id} belum dihantar selepas $jamLepas jam. Hantar reminder...\n";
                $this->sendBelumHantarReminderAsrama($tempahan);
            }
        }
    }

    protected function sendBelumHantarReminderFasiliti($tempahan)
    {
        $user = $tempahan->user;
        if (!$user) return;

        $jenisFasiliti = $tempahan->fasiliti ? $tempahan->fasiliti->nama_fasiliti : 'Tidak Dikenalpasti';
        $tarikhMasuk = date('d-m-Y', strtotime($tempahan->tarikh_masuk));

        Yii::$app->mailer2->compose()
            ->setTo($user->email)
            ->setFrom([Yii::$app->params['adminEmail'] => 'MyFasiliti'])
            ->setSubject('Peringatan Penghantaran Tempahan Fasiliti Belum Selesai')
            ->setHtmlBody("
                <p>Assalamualaikum dan Salam Sejahtera " . Html::encode(strtoupper($user->nama)) . ",</p>

                <p>Puan/Tuan telah membuat tempahan bagi fasiliti <strong>" . Html::encode($jenisFasiliti) . "</strong> pada <strong>$tarikhMasuk</strong>. Walau bagaimanapun, sistem mendapati bahawa tempahan tersebut masih belum dihantar untuk pengesahan pihak pentadbir.</p>

                <p>Bagi memastikan tempahan ini diproses, mohon Puan/Tuan untuk log masuk ke dalam sistem dan menghantar tempahan melalui menu <strong>“Senarai Tempahan Fasiliti”</strong> dengan menekan butang <strong>“Hantar”</strong>.</p>

                <p>Untuk makluman, tempahan tidak akan diproses sekiranya belum dihantar kepada pihak pentadbir.</p>

                <p>Sekian, terima kasih atas perhatian dan kerjasama Puan/Tuan.</p>
            ")
            ->send();
    }


    protected function sendBelumHantarReminderAsrama($tempahan)
    {
        $user = $tempahan->user;
        if (!$user) return;

        $jenisBilik = $tempahan->jenisBilik ? $tempahan->jenisBilik->jenis_bilik : 'Tidak Dikenalpasti';
        $tarikhMasuk = date('d-m-Y', strtotime($tempahan->tarikh_masuk));

        Yii::$app->mailer2->compose()
            ->setTo($user->email)
            ->setFrom([Yii::$app->params['adminEmail'] => 'MyFasiliti'])
            ->setSubject('Peringatan Penghantaran Tempahan Asrama Belum Selesai')
            ->setHtmlBody("
                <p>Assalamualaikum dan Salam Sejahtera " . Html::encode(strtoupper($user->nama)) . ",</p>

                <p>Puan/Tuan telah membuat tempahan bagi penginapan asrama jenis <strong>" . Html::encode($jenisBilik) . "</strong> pada <strong>$tarikhMasuk</strong>. Walau bagaimanapun, sistem mendapati bahawa tempahan tersebut masih belum dihantar untuk pengesahan pihak pentadbir.</p>

                <p>Bagi memastikan tempahan ini diproses, mohon Puan/Tuan untuk log masuk ke dalam sistem dan menghantar tempahan melalui menu <strong>“Senarai Tempahan Asrama”</strong> dengan menekan butang <strong>“Hantar”</strong>.</p>

                <p>Untuk makluman, tempahan tidak akan diproses sekiranya belum dihantar kepada pihak pentadbir.</p>

                <p>Sekian, terima kasih atas perhatian dan kerjasama Puan/Tuan.</p>
            ")
            ->send();
    }



}
