<?php

namespace app\models;


use Yii;

/**
 * This is the model class for table "asrama".
 *
 * @property int $id
 * @property string $blok
 * @property int $aras
 * @property string $no_asrama
 * @property int $status_asrama 0 = kosong, 1 = sedang dibersihkan, 2 = simpanan, 3 = rosak, 4 = risiko, 5 = sedang dibaiki
 * @property int $kelamin 0 = lelaki, 1 = perempuan, 2 = lelaki/perempuan
 * @property int $jenis_asrama_id
 * @property int|null $penginap_kategori_id 
 * @property int|null $kapasiti 
 *
 * @property AsramaStatusLog[] $asramaStatusLogs 
 * @property JenisAsrama $jenisAsrama
 * @property PelajarAsrama[] $pelajarAsramas 
 * @property PenginapKategori $penginapKategori 
 * @property TempahAsrama[] $tempahAsramas
 */ 
class Asrama extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'asrama';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kapasiti'], 'default', 'value' => 1], 
            [['blok', 'aras', 'no_asrama', 'status_asrama', 'kelamin', 'jenis_asrama_id'], 'required'],
            [['aras', 'status_asrama', 'kelamin', 'jenis_asrama_id', 'penginap_kategori_id', 'kapasiti'], 'integer'],
            [['blok'], 'string', 'max' => 10],
            [['no_asrama'], 'string', 'max' => 20],
            [['jenis_asrama_id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisAsrama::class, 'targetAttribute' => ['jenis_asrama_id' => 'id']],
            [['penginap_kategori_id'], 'exist', 'skipOnError' => true, 'targetClass' => PenginapKategori::class, 'targetAttribute' => ['penginap_kategori_id' => 'id']], 
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'blok' => 'Blok',
            'aras' => 'Aras',
            'no_asrama' => 'No Bilik',
            'status_asrama' => 'Status Asrama',
            'kelamin' => 'Kelamin',
            'jenis_asrama_id' => 'Jenis Asrama',
            'penginap_kategori_id' => 'Penginap Kategori',
            'kapasiti' => 'Kapasiti',
        ];
    }
    
    public static function getNamaStatus($code)
    {
        return self::getStatusAsramaList()[$code] ?? 'Tidak Diketahui';
    }
    
    public static function getStatusAsramaList()
    {
        return [
            0 => 'Kosong',
            1 => 'Sedang dibersihkan',
            2 => 'Simpanan',
            3 => 'Rosak',
            4 => 'Risiko',
            5 => 'Sedang dibaiki',
            6 => 'Diisi',
            7 => 'Separa diisi',
        ];
    }

    // public function beforeSave($insert)
    // {
    //     if ($insert) {
    //         // Insert: Create new status log
    //         $log = new \app\models\AsramaStatusLog();
    //         $log->id_asrama = $this->id;
    //         $log->status_log = $this->status_asrama;
    //         $log->tarikh_mula = date('Y-m-d');
    //         $log->save(false);
    //     } else {
    //         // Update: Only log if status changed
    //         $oldStatus = $this->getOldAttribute('status_asrama');

    //         if ($this->status_asrama != $oldStatus) {
    //             // Step 1: Tamatkan log lama
    //             $previousLog = \app\models\AsramaStatusLog::find()
    //                 ->where(['id_asrama' => $this->id])
    //                 ->andWhere(['tarikh_tamat' => null])
    //                 ->orderBy(['tarikh_mula' => SORT_DESC])
    //                 ->one();

    //             if ($previousLog) {
    //                 $previousLog->tarikh_tamat = date('Y-m-d');
    //                 $previousLog->save(false);
    //             }

    //             // Step 2: Log baru
    //             $log = new \app\models\AsramaStatusLog();
    //             $log->id_asrama = $this->id;
    //             $log->status_log = $this->status_asrama;
    //             $log->tarikh_mula = date('Y-m-d');
    //             $log->save(false);
    //         }
    //     }

    //     return parent::beforeSave($insert);
    // }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            // Insert baru – log status awal
            $log = new \app\models\AsramaStatusLog();
            $log->id_asrama = $this->id;
            $log->status_log = $this->status_asrama;
            $log->tarikh_mula = date('Y-m-d');
            $log->save(false);
        } else {
            // Kemaskini – semak jika status berubah
            if (array_key_exists('status_asrama', $changedAttributes) &&
                $this->status_asrama != $changedAttributes['status_asrama']) {

                // Tamatkan log lama
                $previousLog = \app\models\AsramaStatusLog::find()
                    ->where(['id_asrama' => $this->id])
                    ->andWhere(['tarikh_tamat' => null])
                    ->orderBy(['tarikh_mula' => SORT_DESC])
                    ->one();

                if ($previousLog) {
                    $previousLog->tarikh_tamat = date('Y-m-d');
                    $previousLog->save(false);
                }

                // Log baru
                $log = new \app\models\AsramaStatusLog();
                $log->id_asrama = $this->id;
                $log->status_log = $this->status_asrama;
                $log->tarikh_mula = date('Y-m-d');
                $log->save(false);
            }
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * Gets query for [[JenisAsrama]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJenisAsrama()
    {
        return $this->hasOne(JenisAsrama::class, ['id' => 'jenis_asrama_id']);
    }

    /**
    * Gets query for [[PelajarAsramas]]. 
    * 
    * @return \yii\db\ActiveQuery 
    */ 
    public function getPelajarAsramas() 
    { 
        return $this->hasMany(PelajarAsrama::class, ['id_asrama' => 'id']); 
    } 
    
    /** 
    * Gets query for [[PenginapKategori]]. 
    * 
    * @return \yii\db\ActiveQuery 
    */ 
    public function getPenginapKategori() 
    { 
        return $this->hasOne(PenginapKategori::class, ['id' => 'penginap_kategori_id']); 
    } 

    /**
     * Gets query for [[TempahAsramas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTempahAsramas()
    {
        return $this->hasMany(TempahAsrama::class, ['id_asrama' => 'id']);
    }

    public function statusLog()
    {
        return $this->hasMany(AsramaStatusLog::class, 'asrama_id');
    }


}
