<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "asrama_status_log".
 *
 * @property int $id
 * @property int $id_asrama
 * @property int $status_asrama 0=Kosong, 1=S.Dibersihkan, 2=Simpanan, 3=Rosak, 4=Risiko, 5=S.Dibaiki, 6=Diisi
 * @property string $tarikh_mula
 * @property string|null $tarikh_tamat
 *
 * @property Asrama $asrama
 */
class AsramaStatusLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'asrama_status_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tarikh_tamat'], 'default', 'value' => null],
            [['id_asrama', 'status_asrama', 'tarikh_mula'], 'required'],
            [['id_asrama', 'status_asrama'], 'integer'],
            [['tarikh_mula', 'tarikh_tamat'], 'safe'],
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
            'id_asrama' => 'Id Asrama',
            'status_asrama' => 'Status Asrama',
            'tarikh_mula' => 'Tarikh Mula',
            'tarikh_tamat' => 'Tarikh Tamat',
        ];
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

    public static function getStatusList()
{
    return [
        0 => 'Kosong',
        1 => 'Sedang Dibersihkan',
        2 => 'Simpanan',
        3 => 'Rosak',
        4 => 'Risiko',
        5 => 'Sedang Dibaiki',
        6 => 'Diisi',
    ];
}

public function getStatusText()
{
    return self::getStatusList()[$this->status] ?? 'Unknown';
}


}
