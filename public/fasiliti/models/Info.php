<?php

namespace app\models;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use Yii;

/**
 * This is the model class for table "info".
 *
 * @property int $id
 * @property string $tajuk
 * @property string|null $keterangan
 * @property int $aktif
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $fasiliti_id 
 */
class Info extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'info';
    }

    /**
     * {@inheritdoc}
     */
     // ✅ Tambah method ni
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    self::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function rules()
    {
        return [
            [['keterangan', 'fasiliti_id'], 'default', 'value' => null],
            [['aktif'], 'default', 'value' => 1],
            [['tajuk'], 'required'],
            [['keterangan'], 'string'],
            [['aktif', 'fasiliti_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['tajuk'], 'string', 'max' => 255],
            
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tajuk' => 'Tajuk',
            'keterangan' => 'Keterangan',
            'aktif' => 'Aktif',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'fasiliti_id' => 'Fasiliti ID', 
        ];
    }

}
