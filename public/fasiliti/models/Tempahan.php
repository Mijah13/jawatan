<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;

class Tempahan extends ActiveRecord
{
    public static function tableName()
    {
        return 'tempahan';
    }

    public function rules()
    {
        return [
            [['info', 'startdate', 'enddate', 'room_id'], 'required'],
            ['info', 'string'],
            ['room_id', 'integer'],
            [['startdate', 'enddate'], 'date', 'format' => 'php:Y-m-d'],
            ['enddate', 'compare', 
                'compareAttribute' => 'startdate', 
                'operator' => '>', 
                'message' => 'Tarikh tamat mestilah selepas tarikh mula.'
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'room_id' => 'Room ID',
            'info' => 'Maklumat Tempahan',
            'startdate' => 'Tarikh Mula',
            'enddate' => 'Tarikh Tamat',
        ];
    }

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            // Convert d-m-Y to Y-m-d for database storage
            if ($this->startdate) {
                $this->startdate = Yii::$app->formatter->asDate(
                    $this->startdate, 
                    'php:Y-m-d'
                );
            }
            if ($this->enddate) {
                $this->enddate = Yii::$app->formatter->asDate(
                    $this->enddate, 
                    'php:Y-m-d'
                );
            }
            return true;
        }
        return false;
    }

    public function afterFind()
    {
        parent::afterFind();
        // Convert Y-m-d to d-m-Y for display
        if ($this->startdate) {
            $this->startdate = Yii::$app->formatter->asDate($this->startdate, 'php:d-m-Y');
        }
        if ($this->enddate) {
            $this->enddate = Yii::$app->formatter->asDate($this->enddate, 'php:d-m-Y');
        }
    }
} 
