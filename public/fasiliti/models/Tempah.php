<?php

namespace app\models;
use app\models\Json;

use Yii;

/**
 * This is the model class for table "tempah".
 *
 * @property int $id
 * @property int $user_id
 * @property string $nama_pemohon
 * @property string $no_kp_pemohon
 * @property string|null $agensi_pemohon
 * @property string|null $tujuan_nama_kursus
 * @property string $tarikh_masuk
 * @property string $tarikh_keluar
 * @property string|null $no_tel
 * @property string|null $alamat
 * @property string|null $email
 * @property int $jenis_fasiliti
 * @property int|null $jangkaan_hadirin
 * @property string|null $peralatan
 * @property string|null $lain_peralatan
 * @property int $jenis_penginap
 * @property string|null $kod_kursus
 * @property string|null $sesi_batch
 * @property string|null $status
 * @property string|null $masalah_kesihatan
 * @property string|null $jenis_bilik
 * @property string|null $jantina
 * @property string|null $nama_peserta
 * @property string|null $no_kp_peserta
 * @property string|null $no_tel_peserta
 * @property string|null $alamat_peserta
 * @property string|null $email_peserta
 * @property string|null $jenis_bilik_peserta
 * @property int|null $bilangan_lelaki
 * @property int|null $bilangan_perempuan
 *
 * @property Admin $admin
 * @property PenginapKategori $jenisPenginap
 * @property User $user
 */

class Tempah extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tempah';
    }

    // Attributes to store participant information as JSON
    public $nama_peserta = [];
    public $no_kp_peserta = [];
    public $no_tel_peserta = [];
    public $email_peserta = [];
    public $jenis_bilik_peserta = [];

    /**
     * {@inheritdoc}
     */
    public function rules()
{
    return [
        // Set all fields as required except for 'lain_peralatan' and 'masalah_kesihatan'
        [['user_id', 'nama_pemohon', 'no_kp_pemohon', 'tarikh_masuk', 'tarikh_keluar', 'jenis_fasiliti', 'agensi_pemohon', 'tujuan_nama_kursus', 'no_tel', 'alamat', 'email'], 'required', 'message' => 'Sila masukkan {attribute}.'],

        // Define 'integer' validation for integer fields
        [['user_id', 'jenis_fasiliti', 'jangkaan_hadirin', 'jenis_penginap', 'bilangan_lelaki', 'bilangan_perempuan', 'jantina', 'status'], 'integer'],

        // Define 'safe' validation for date fields
        [['tarikh_masuk', 'tarikh_keluar', 'peralatan', 'nama_peserta', 'no_kp_peserta', 'no_tel_peserta', 'alamat_peserta', 'email_peserta', 'jenis_bilik_peserta'], 'safe'],

        // Define 'string' validation for specific fields
        [['alamat'], 'string'],
        [['nama_pemohon', 'no_kp_pemohon', 'agensi_pemohon', 'tujuan_nama_kursus', 'no_tel', 'email', 'lain_peralatan', 'kod_kursus', 'sesi_batch', 'jenis_bilik'], 'string', 'max' => 255],
       
      

        [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
    ];
   
}

// Override the beforeSave function to encode arrays to JSON
public function beforeSave($insert)
{
    if (parent::beforeSave($insert)) {
        $this->nama_peserta = is_array($this->nama_peserta) ? json_encode($this->nama_peserta) : $this->nama_peserta;
        $this->no_kp_peserta = is_array($this->no_kp_peserta) ? json_encode($this->no_kp_peserta) : $this->no_kp_peserta;
        $this->no_tel_peserta = is_array($this->no_tel_peserta) ? json_encode($this->no_tel_peserta) : $this->no_tel_peserta;
        $this->email_peserta = is_array($this->email_peserta) ? json_encode($this->email_peserta) : $this->email_peserta;
        $this->jenis_bilik_peserta = is_array($this->jenis_bilik_peserta) ? json_encode($this->jenis_bilik_peserta) : $this->jenis_bilik_peserta;
        return true;
    }
    return false;
}


// Override the afterFind function to decode JSON back into arrays
public function afterFind()
{
    parent::afterFind();

    $this->nama_peserta = is_string($this->nama_peserta) ? json_decode($this->nama_peserta, true) : $this->nama_peserta;
    $this->no_kp_peserta = is_string($this->no_kp_peserta) ? json_decode($this->no_kp_peserta, true) : $this->no_kp_peserta;
    $this->no_tel_peserta = is_string($this->no_tel_peserta) ? json_decode($this->no_tel_peserta, true) : $this->no_tel_peserta;
    $this->email_peserta = is_string($this->email_peserta) ? json_decode($this->email_peserta, true) : $this->email_peserta;
    $this->jenis_bilik_peserta = is_string($this->jenis_bilik_peserta) ? json_decode($this->jenis_bilik_peserta, true) : $this->jenis_bilik_peserta;
}



    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'nama_pemohon' => 'Nama Pemohon',
            'no_kp_pemohon' => 'No Kp Pemohon',
            'agensi_pemohon' => 'Agensi Pemohon',
            'tujuan_nama_kursus' => 'Tujuan Nama Kursus',
            'tarikh_masuk' => 'Tarikh Masuk',
            'tarikh_keluar' => 'Tarikh Keluar',
            'no_tel' => 'No Tel',
            'alamat' => 'Alamat',
            'email' => 'Email',
            'jenis_fasiliti' => 'Jenis Fasiliti',
            'jangkaan_hadirin' => 'Jangkaan Hadirin',
            'peralatan' => 'Peralatan',
            'lain_peralatan' => 'Lain Peralatan',
            'jenis_penginap' => 'Jenis Penginap',
            'kod_kursus' => 'Kod Kursus',
            'sesi_batch' => 'Sesi Batch',
            'status' => 'Status',
            'masalah_kesihatan' => 'Masalah Kesihatan',
            'jenis_bilik' => 'Jenis Bilik',
            'jantina' => 'Jantina',
            'nama_peserta' => 'Nama Peserta',
            'no_kp_peserta' => 'No Kp Peserta',
            'no_tel_peserta' => 'No Tel Peserta',
            'alamat_peserta' => 'Alamat Peserta',
            'email_peserta' => 'Email Peserta',
            'jenis_bilik_peserta' => 'Jenis Bilik Peserta',
            'bilangan_lelaki' => 'Bilangan Lelaki',
            'bilangan_perempuan' => 'Bilangan Perempuan',
            
        ];
    }

    /**
     * Gets query for [[JenisPenginap]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJenisPenginap()
    {
        return $this->hasOne(PenginapKategori::class, ['id_penginap' => 'jenis_penginap']);
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
