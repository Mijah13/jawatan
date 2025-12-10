<?php

namespace app\models;
use yii\web\UploadedFile;

use Yii;

/**
 * This is the model class for table "jenis_asrama".
 *
 * @property int $id
 * @property string $jenis_bilik
 * @property string $deskripsi 
 * @property float $kadar_sewa
 * @property int $asrama_id
 * @property string|null $gambar 
 * @property int $akses_pengguna
 *
 * @property TempahAsrama[] $tempahAsramas
 * @property Asrama[] $asramas 
 * @property PelajarAsrama[] $pelajarAsramas 
 */
class JenisAsrama extends \yii\db\ActiveRecord
{
    // Fasiliti model
    public $imej; // Make sure you have this in your model to handle image upload

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'jenis_asrama';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gambar'], 'default', 'value' => null], 
            [['asrama_id'], 'default', 'value' => 13], 
            [['akses_pengguna'], 'default', 'value' => 0], 
            [['jenis_bilik', 'deskripsi', 'kadar_sewa'], 'required'],
            [['deskripsi', 'gambar'], 'string'],
            [['kadar_sewa'], 'number'],
            [['asrama_id', 'akses_pengguna'], 'integer'],
            [['jenis_bilik'], 'string', 'max' => 255],
            [['imej'],'file', 'skipOnEmpty' => true, 'extensions' => 'jpg, JPG, jpeg, png, gif', 'maxFiles' => 5, 'maxSize' => 1024 * 1024 * 10], // 10MB max size
            [['asrama_id'], 'exist', 'skipOnError' => true, 'targetClass' => TempahAsrama::class, 'targetAttribute' => ['asrama_id' => 'id']], 
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'jenis_bilik' => 'Jenis Bilik',
            'deskripsi' => 'Deskripsi',
            'kadar_sewa' => 'Kadar Sewa',
            'asrama_id' => 'Asrama ID',
            'gambar' => 'Gambar',
            'akses_pengguna' => 'Akses Pengguna', 
        ];
    }


 
   /** 
     * Gets query for [[TempahAsramas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTempahAsramas()
    {
        return $this->hasMany(TempahAsrama::class, ['jenis_bilik' => 'id']);
    }

    /** 
    * Gets query for [[Asramas]]. 
    * 
    * @return \yii\db\ActiveQuery 
    */ 
    public function getAsramas() 
    { 
        return $this->hasMany(Asrama::class, ['jenis_asrama_id' => 'id']); 
    } 

    /**
    * Gets query for [[PelajarAsramas]].
    *
    * @return \yii\db\ActiveQuery
    */
    public function getPelajarAsramas()
    {
        return $this->hasMany(PelajarAsrama::class, ['jenis_bilik' => 'id']);
    }
		
}
