<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fasiliti_status_log".
 *
 * @property int $id
 * @property int $fasiliti_id
 * @property int $fasiliti_status 0=Kosong, 1=S.Dibersihkan, 2=Simpanan, 3=Rosak, 4=Risiko, 5=S.Dibaiki, 6=Diisi
 * @property string $tarikh_mula
 * @property string|null $tarikh_tamat
 *
 * @property Fasiliti $fasiliti
 */
class FasilitiStatusLog extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fasiliti_status_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tarikh_tamat'], 'default', 'value' => null],
            [['fasiliti_id', 'fasiliti_status', 'tarikh_mula'], 'required'],
            [['fasiliti_id', 'fasiliti_status'], 'integer'],
            [['tarikh_mula', 'tarikh_tamat'], 'safe'],
            [['fasiliti_id'], 'exist', 'skipOnError' => true, 'targetClass' => Fasiliti::class, 'targetAttribute' => ['fasiliti_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fasiliti_id' => 'Fasiliti ID',
            'fasiliti_status' => 'Status Fasiliti',
            'tarikh_mula' => 'Tarikh Mula',
            'tarikh_tamat' => 'Tarikh Tamat',
        ];
    }

    /**
     * Gets query for [[Fasiliti]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFasiliti()
    {
        return $this->hasOne(Fasiliti::class, ['id' => 'fasiliti_id']);
    }

}
