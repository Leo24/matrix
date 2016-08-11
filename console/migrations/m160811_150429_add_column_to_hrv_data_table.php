<?php

use yii\db\Migration;

class m160811_150429_add_column_to_hrv_data_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%hrv_data}}', 'timestamp', \yii\db\mysql\Schema::TYPE_INTEGER . '(11)');
    }

    public function down()
    {
        $this->dropColumn('{{%hrv_data}}', 'timestamp');
    }
}
