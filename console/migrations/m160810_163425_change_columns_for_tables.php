<?php

use yii\db\Migration;

class m160810_163425_change_columns_for_tables extends Migration
{

    public function safeUp()
    {
        $this->alterColumn('{{%sleep_quality}}', 'from', \yii\db\mysql\Schema::TYPE_INTEGER . '(11)');
        $this->alterColumn('{{%sleep_quality}}', 'to', \yii\db\mysql\Schema::TYPE_INTEGER . '(11)');
        $this->dropColumn('{{%sleep_quality}}', 'timestamp');
    }

    public function safeDown()
    {
        $this->alterColumn('{{%sleep_quality}}', 'from', \yii\db\mysql\Schema::TYPE_STRING . '(256)');
        $this->alterColumn('{{%sleep_quality}}', 'to', \yii\db\mysql\Schema::TYPE_STRING . '(256)');
        $this->addColumn('{{%sleep_quality}}', 'timestamp', \yii\db\mysql\Schema::TYPE_INTEGER . '(11)');
    }

}
