<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "status_fasiliti".
 *
 * @property int $id
 * @property string $kekosongan
 * @property int $start_time
 * @property string $end_time
 * 
 *
 * @property JenisFasiliti $id0
 */
class StatusFasiliti extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'status_fasiliti';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'kekosongan'], 'required'],
            [['id'], 'integer'],
            [['kekosongan', 'start_time', 'end_time'], 'safe'], // Include start_time and end_time as safe
            [['id', 'kekosongan'], 'unique', 'targetAttribute' => ['id', 'kekosongan']],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => JenisFasiliti::class, 'targetAttribute' => ['id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kekosongan' => 'Kekosongan',
            'start_time' => 'Start Time', // Add label for start time
            'end_time' => 'End Time', // Add label for end time
        ];
    }

    /**
     * Gets query for [[Id0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getId0()
    {
        return $this->hasOne(JenisFasiliti::class, ['id' => 'id']);
    }
     /**
     * Format the status for FullCalendar.
     *
     * @return array
     */
    public function toEventFormat()
    {
        return [
            'id' => $this->id,
            'title' => $this->kekosongan,
            'start' => $this->start_time,
            'end' => $this->end_time,
        ];
    }
}
