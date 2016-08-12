<?php

use yii\db\Migration;

class m160812_063215_add_columns_for_sleep_quality_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%sleep_quality}}', 'hrv_score', \yii\db\mysql\Schema::TYPE_FLOAT . '(11)');
        $this->addColumn('{{%sleep_quality}}', 'hrv_lf', \yii\db\mysql\Schema::TYPE_FLOAT . '(11)');
        $this->addColumn('{{%sleep_quality}}', 'hrv_hf', \yii\db\mysql\Schema::TYPE_FLOAT . '(11)');
        $this->addColumn('{{%sleep_quality}}', 'hrv_rmssd_evening', \yii\db\mysql\Schema::TYPE_FLOAT . '(11)');
        $this->addColumn('{{%sleep_quality}}', 'hrv_rmssd_morning', \yii\db\mysql\Schema::TYPE_FLOAT . '(11)');
    }

    public function safeDown()
    {
        $this->dropColumn('{{%sleep_quality}}', 'hrv_score');
        $this->dropColumn('{{%sleep_quality}}', 'hrv_lf');
        $this->dropColumn('{{%sleep_quality}}', 'hrv_hf');
        $this->dropColumn('{{%sleep_quality}}', 'hrv_rmssd_evening');
        $this->dropColumn('{{%sleep_quality}}', 'hrv_rmssd_morning');
    }

}
