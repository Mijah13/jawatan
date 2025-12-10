<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%fasiliti_status_log}}`.
 * Has foreign keys to the tables:
 *
 * - `fasiliti`
 */
class m241104_025241_change_datatype extends Migration
{
    public function safeUp()
    {
        // 1. Convert existing data from string to integer
        Yii::$app->db->createCommand("
            UPDATE tempah_fasiliti SET tempoh = 
            CASE tempoh
                WHEN 'sesiPagi' THEN 1
                WHEN 'sesiPetang' THEN 2
                WHEN 'sesiMalam' THEN 3
                WHEN 'sesiPagiPetang' THEN 4
                WHEN 'satuHari' THEN 5
                ELSE NULL
            END
        ")->execute();

        // 2. Change the column type to INTEGER
        $this->alterColumn('tempah_fasiliti', 'tempoh', $this->integer());
    }

    public function safeDown()
    {
        // 1. Convert back integer to original string
        Yii::$app->db->createCommand("
            UPDATE tempah_fasiliti SET tempoh = 
            CASE tempoh
                WHEN 1 THEN 'sesiPagi'
                WHEN 2 THEN 'sesiPetang'
                WHEN 3 THEN 'sesiMalam'
                WHEN 4 THEN 'sesiPagiPetang'
                WHEN 5 THEN 'satuHari'
                ELSE NULL
            END
        ")->execute();

        // 2. Change column back to STRING
        $this->alterColumn('tempah_fasiliti', 'tempoh', $this->string());
    }
}