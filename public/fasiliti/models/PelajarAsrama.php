<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pelajar_asrama".
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $id_asrama
 * @property string|null $tarikh_masuk 
 * @property string|null $tarikh_keluar 
 * @property string|null $tarikh_pembersihan
 * @property string|null $no_kp
 * @property string|null $no_tel
 * @property string|null $email
 * @property string|null $kod_kursus
 * @property string|null $sesi_batch
 * @property int|null $status
 * @property int|null $jantina
 * @property string|null $alamat
 * @property int|null $jenis_bilik
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int $status_penginapan 0 = Asrama, 1 = Luar Asrama 
 * @property string|null $hubungan
 * @property string|null $no_waris
 *
 * @property Asrama $asrama
 * @property JenisAsrama $jenisBilik
 * @property User $user
 */
class PelajarAsrama extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pelajar_asrama';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_asrama', 'tarikh_masuk', 'tarikh_keluar', 'tarikh_pembersihan', 'no_kp', 'no_tel', 'email', 'kod_kursus', 'sesi_batch', 'status', 'jantina', 'alamat', 'jenis_bilik', 'hubungan', 'no_waris'], 'default', 'value' => null],
            [['user_id', 'no_kp', 'no_tel', 'kod_kursus', 'sesi_batch', 'status', 'jantina', 'alamat'], 'required'],
            [['status_penginapan'], 'default', 'value' => 0],
            [['no_kp'], 'validateUniqueICKursus'],
            [['user_id', 'id_asrama', 'status', 'jantina', 'jenis_bilik', 'status_penginapan'], 'integer'],
            ['no_kp', 'match', 'pattern' => '/^\d{12}$/', 'message' => 'Nombor kad pengenalan mesti 12 digit tanpa (-).'],
            ['no_tel', 'match', 'pattern' => '/^\d{10,11}$/', 'message' => 'Nombor telefon mesti tidak melebihi 11 digit tanpa (-).'],
            [['tarikh_masuk', 'tarikh_keluar', 'tarikh_pembersihan', 'created_at', 'updated_at'], 'safe'],
            [['no_kp', 'no_tel', 'email', 'alamat'], 'string', 'max' => 255],
            [['kod_kursus', 'sesi_batch'], 'string', 'max' => 50],
            [['hubungan'], 'string', 'max' => 100], 
            [['no_waris'], 'string', 'max' => 20], 
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['jenis_bilik'], 'exist', 'skipOnError' => true, 'targetClass' => JenisAsrama::class, 'targetAttribute' => ['jenis_bilik' => 'id']],
            [['id_asrama'], 'exist', 'skipOnError' => true, 'targetClass' => Asrama::class, 'targetAttribute' => ['id_asrama' => 'id']],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'id_asrama' => 'Id Asrama',
            'tarikh_masuk' => 'Tarikh Masuk',
		    'tarikh_keluar' => 'Tarikh Keluar',
            'tarikh_pembersihan' => 'Tarikh Pembersihan',
            'no_kp' => 'No. KP',
            'no_tel' => 'No. Tel',
            'email' => 'Email',
            'kod_kursus' => 'Kod Kursus',
            'sesi_batch' => 'Sesi Batch',
            'status' => 'Status',
            'jantina' => 'Jantina',
            'alamat' => 'Alamat',
            'jenis_bilik' => 'Jenis Bilik',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status_penginapan' => 'Status Penginapan',
            'hubungan' => 'Hubungan', 
            'no_waris' => 'No. Waris', 
        ];
    }

    public function validateUniqueICKursus($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $exists = self::find()
                ->where([
                    'no_kp' => $this->no_kp,
                    'kod_kursus' => $this->kod_kursus,
                ])
                ->andWhere(['<>', 'id', $this->id]) // abaikan kalau tengah edit
                ->exists();

            if ($exists) {
                $this->addError($attribute, 'Rekod telah disimpan dengan No. Pengenalan yang sama.');
            }
        }
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // --- Auto tambah tahun semasa dalam sesi_batch kalau belum ada ---
            $currentYear = date('Y');
            $batchYear = '/' . $currentYear; // contoh: /2025

            $batchArray = explode(' ', $this->sesi_batch);
            if (!in_array($batchYear, $batchArray)) {
                $this->sesi_batch = trim($this->sesi_batch . ' ' . $batchYear);
            }

            // --- Auto set tarikh_pembersihan 5 hari selepas tarikh_keluar ---
            if ($this->tarikh_keluar) {
                $this->tarikh_pembersihan = date('Y-m-d', strtotime($this->tarikh_keluar . ' +5 days'));
            }

            // if ($this->status_penginapan == 1) {
            //     $this->id_asrama = null;
            // }

        // --- Standardize huruf besar untuk teks tertentu ---
            $this->alamat = strtoupper($this->alamat);

            return true;
        }
        return false;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // Kalau status_penginapan ditukar ke 1 (tinggal luar)
        if ($this->status_penginapan == 1 && $this->id_asrama) {

            $asrama = \app\models\Asrama::findOne($this->id_asrama);

            if ($asrama && $asrama->status_asrama == 6) { // asalnya Diisi
                $asrama->status_asrama = 0; // tukar ke Kosong
                $asrama->save(false);

                // Optional: rekod log perubahan status
                $log = new \app\models\AsramaStatusLog();
                $log->id_asrama = $asrama->id;
                $log->status_log = 0;
                $log->tarikh_mula = date('Y-m-d');
                $log->tarikh_tamat = date('Y-m-d');
                // $log->catatan = 'Auto kosong selepas pelajar tukar ke luar asrama';
                $log->save(false);
            }

            // Clear bilik dari pelajar
            $this->updateAttributes(['id_asrama' => null]);
        }
    }


    /**
     * Gets query for [[Asrama]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAsrama()
    {
        return $this->hasOne(Asrama::class, ['id' => 'id_asrama']);
    }

    /**
     * Gets query for [[JenisBilik]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJenisBilik()
    {
        return $this->hasOne(JenisAsrama::class, ['id' => 'jenis_bilik']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}
