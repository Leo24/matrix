<?php

use yii\db\Migration;

class m160729_090337_create_sleep_data_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%sleep_data}}', [
            'id'         => $this->primaryKey(),
            'user_id'    => $this->integer(11)->notNull(),
            'timestamp'  => $this->bigInteger(),
            'sleep_type' => $this->float(),
            'created_at' => $this->integer(11)->unsigned()->defaultValue(null),
            'updated_at' => $this->integer(11)->unsigned()->defaultValue(null),
        ], $tableOptions);

        $this->createIndex('idx_sleep_data_user_id', '{{%sleep_data}}', 'user_id');

        $this->addForeignKey(
            'fk_tbl_sleep_data_tbl_user',
            '{{%sleep_data}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_tbl_sleep_data_tbl_user', '{{%sleep_data}}');
        $this->dropIndex('idx_sleep_data_user_id', '{{%sleep_data}}');
        $this->dropTable('{{%sleep_data}}');
    }
}
