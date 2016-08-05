<?php

use yii\db\Migration;

class m160729_090337_create_sleep_data_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%sleep_data}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->notNull(),
            'timestamp' => $this->bigInteger(),
            'sleep_type' => $this->float(),
        ], $tableOptions);
        $this->addForeignKey('fk_tbl_sleep_data_tbl_user',
            '{{%sleep_data}}', 'user_id',
            '{{%user}}', 'id',
            'CASCADE', 'CASCADE');
    }
    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_tbl_sleep_data_tbl_user', '{{%sleep_data}}');
        $this->dropTable('{{%sleep_data}}');
    }
}
