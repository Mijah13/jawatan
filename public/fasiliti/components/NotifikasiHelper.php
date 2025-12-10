<?php
namespace app\components;

use app\models\TempahAsrama;
use app\models\TempahFasiliti;
use app\models\PelajarAsrama;
use Yii;

class NotifikasiHelper
{
    public static function getActionNeededCountByRole($role)
    {
        $notifikasi = [];

        if (in_array($role, [0, 6, 1])) {
            // Role ini nampak SEMUA fasiliti KECUALI fasiliti_id = 20
            $notifikasi['tempahanBaruAsrama'] = TempahAsrama::find()
                ->where(['status_tempahan_adminKemudahan' => 1])
                ->count();

            $notifikasi['tempahanBaruFasiliti'] = TempahFasiliti::find()
                ->where(['status_tempahan_adminKemudahan' => 1])
                ->andWhere(['!=', 'fasiliti_id', 20])
                ->count();

            $notifikasi['pelajarBaru'] = PelajarAsrama::find()
                ->where(['tarikh_masuk' => null])
                ->count();
        }

        if (in_array($role, [0, 8])) {
            // Role 8 hanya nampak fasiliti_id = 20 sahaja
            $notifikasi['tempahanBaruFasiliti'] = TempahFasiliti::find()
                ->where([
                    'status_tempahan_adminKemudahan' => 1,
                    'fasiliti_id' => 20
                ])
                ->count();
        }


        if ($role == 7) {
            $notifikasi['asramaMenungguBayaran'] = TempahAsrama::find()
                ->where([
                    'status_pembayaran' => 2,
                    'status_tempahan_adminKemudahan' => 2,
                    'status_tempahan_pelulus' => 2
                ])
                ->count();

            $notifikasi['fasilitiMenungguBayaran'] = TempahFasiliti::find()
                ->where([
                    'status_pembayaran' => 2,
                    'status_tempahan_adminKemudahan' => 2,
                    'status_tempahan_pelulus' => 2
                ])
                ->count();
        }

        if (in_array($role, [0, 2])) {
            $notifikasi['asramaPerluLulus'] = TempahAsrama::find()->where(['status_tempahan_pelulus' => 1])->count();
            $notifikasi['fasilitiPerluLulus'] = TempahFasiliti::find()->where(['status_tempahan_pelulus' => 1])->count();
        }
        
        if (in_array($role, [0, 3, 4])) {
            $currentUserId = Yii::$app->user->id;

            $notifikasi['asramaPerluHantar'] = TempahAsrama::find()
                ->where(['status_tempahan_adminKemudahan' => 0, 'user_id' => $currentUserId])
                ->count();

            $notifikasi['fasilitiPerluHantar'] = TempahFasiliti::find()
                ->where(['status_tempahan_adminKemudahan' => 0, 'user_id' => $currentUserId])
                ->count();
        }

        return $notifikasi;
    }
}