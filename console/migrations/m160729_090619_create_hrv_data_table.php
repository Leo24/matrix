<?php

use yii\db\Migration;

class m160729_090619_create_hrv_data_table extends Migration
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
        $this->createTable('{{%hrv_data}}', [
            'id'             => $this->primaryKey(),
            'user_id'        => $this->integer(11)->notNull(),
            'start_rmssd'    => $this->float(),
            'end_rmssd'      => $this->float(),
            'total_recovery' => $this->float(),
            'recovery_ratio' => $this->float(),
            'recovery_rate'  => $this->float(),
            'created_at'     => $this->integer(11)->unsigned()->defaultValue(null),
            'updated_at'     => $this->integer(11)->unsigned()->defaultValue(null),
        ], $tableOptions);

        $this->createIndex('idx_hrv_data_user_id', '{{%hrv_data}}', 'user_id');

        $this->addForeignKey(
            'fk_tbl_hrv_data_tbl_user',
            '{{%hrv_data}}',
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
        $this->dropForeignKey('fk_tbl_hrv_data_tbl_user', '{{%hrv_data}}');
        $this->dropIndex('idx_hrv_data_user_id', '{{%hrv_data}}');
        $this->dropTable('{{%hrv_data}}');
    }
}
