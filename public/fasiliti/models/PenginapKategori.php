<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "penginap_kategori".
 *
 * @property int $id
 * @property string $jenis_penginap
 */
class PenginapKategori extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'penginap_kategori';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['jenis_penginap'], 'required'],
            [['jenis_penginap'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'jenis_penginap' => 'Jenis Penginap',
        ];
    }
}
