<?php

namespace app\models;
use Yii;

/**
 * This is the model class for table "fasiliti".
 *
 * @property int $id
 * @property string $nama_fasiliti
 * @property string $deskripsi
 * @property float|null $kadar_sewa_perJam
 * @property float|null $kadar_sewa_perHari
 * @property float|null $kadar_sewa_perJamSiang
 * @property float|null $kadar_sewa_perJamMalam
 * @property int $fasiliti_status 0 = kosong, 1 = ditempah, 2 = disimpan, 3 = rosak 
 * @property string|null gambar
 * @property int $akses_pengguna 0 = semua, 1 = dalaman
 * 
 * @property FasilitiStatusLog[] $fasilitiStatusLogs
 * 
 * 
 * @property TempahFasiliti[] $tempahFasilitis
 */
class Fasiliti extends \yii\db\ActiveRecord
{
    // Fasiliti model
    public $imej; // Make sure you have this in your model to handle image upload
    public static $skipLog = false;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fasiliti';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nama_fasiliti', 'deskripsi', 'fasiliti_status'], 'required'],
            [['deskripsi'], 'string'],
            [['kadar_sewa_perJam', 'kadar_sewa_perHari', 'kadar_sewa_perJamSiang', 'kadar_sewa_perJamMalam'], 'number'],
            [['kadar_sewa_perJam', 'kadar_sewa_perHari', 'kadar_sewa_perJamSiang', 'kadar_sewa_perJamMalam'], 'default', 'value' => null],
            [['akses_pengguna'], 'default', 'value' => 0], 
            [['fasiliti_status', 'akses_pengguna'], 'integer'],
            [['nama_fasiliti'], 'string', 'max' => 255],
            [['imej'],'file', 'skipOnEmpty' => true, 'extensions' => 'jpg, JPG, jpeg, png, gif', 'maxSize' => 1024 * 1024 * 10], // 10MB max size
            [['gambar'], 'string', 'max' => 255], // Image path column validation
          
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama_fasiliti' => 'Nama Fasiliti',
            'deskripsi' => 'Deskripsi',
            'kadar_sewa_perJam' => 'Kadar Sewa/Jam',
            'kadar_sewa_perHari' => 'Kadar Sewa/Hari',
            'kadar_sewa_perJamSiang' => 'Kadar Sewa/Jam Siang',
            'kadar_sewa_perJamMalam' => 'Kadar Sewa/Jam Malam',
            'fasiliti_status' => 'Fasiliti Status', 
            'gambar' => 'Gambar',
            'akses_pengguna' => 'Akses Pengguna', 
        ];
    }

    // public function beforeSave($insert)
    // {
    //     if ($insert) {
    //         // Insert: Create new status log
    //         $log = new \app\models\FasilitiStatusLog();
    //         $log->fasiliti_id = $this->id;
    //         $log->fasiliti_status = $this->fasiliti_status;
    //         $log->tarikh_mula = date('Y-m-d');
    //         $log->save(false);
    //     } else {
    //         // Update: Only log if status changed
    //         // $oldStatus = self::findOne($this->id)->fasiliti_status;
    //         $oldStatus = $this->getOldAttribute('fasiliti_status');

    
    //         if ($this->fasiliti_status != $oldStatus) {
    //             // Step 1: Tamatkan log lama
    //             $previousLog = \app\models\FasilitiStatusLog::find()
    //                 ->where(['fasiliti_id' => $this->id])
    //                 ->andWhere(['tarikh_tamat' => null])
    //                 ->orderBy(['tarikh_mula' => SORT_DESC])
    //                 ->one();
    
    //             if ($previousLog) {
    //                 $previousLog->tarikh_tamat = date('Y-m-d');
    //                 $previousLog->save(false);
    //             }
    
    //             // Step 2: Log baru
    //             $log = new \app\models\FasilitiStatusLog();
    //             $log->fasiliti_id = $this->id;
    //             $log->fasiliti_status = $this->fasiliti_status;
    //             $log->tarikh_mula = date('Y-m-d');
    //             $log->save(false);
    //         }
    //     }
    //       return parent::beforeSave($insert);
    // }
    

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            // Fasiliti baru – buat log baru
            $log = new \app\models\FasilitiStatusLog();
            $log->fasiliti_id = $this->id; // Sekarang ID memang dah wujud
            $log->fasiliti_status = $this->fasiliti_status;
            $log->tarikh_mula = date('Y-m-d');
            $log->save(false);
        } else {
            // Fasiliti dikemaskini – check kalau status berubah
            if (array_key_exists('fasiliti_status', $changedAttributes) && 
                $this->fasiliti_status != $changedAttributes['fasiliti_status']) {

                // Tamatkan log lama
                $previousLog = \app\models\FasilitiStatusLog::find()
                    ->where(['fasiliti_id' => $this->id])
                    ->andWhere(['tarikh_tamat' => null])
                    ->orderBy(['tarikh_mula' => SORT_DESC])
                    ->one();

                if ($previousLog) {
                    $previousLog->tarikh_tamat = date('Y-m-d');
                    $previousLog->save(false);
                }

                // Buat log baru
                $log = new \app\models\FasilitiStatusLog();
                $log->fasiliti_id = $this->id;
                $log->fasiliti_status = $this->fasiliti_status;
                $log->tarikh_mula = date('Y-m-d');
                $log->save(false);
            }
        }

        parent::afterSave($insert, $changedAttributes);
    }


    /**
     * Gets query for [[TempahFasilitis]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTempahFasilitis()
    {
        return $this->hasMany(TempahFasiliti::class, ['fasiliti_id' => 'id']);
    }
    

}
