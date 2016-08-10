<?php

use yii\db\Migration;

class m160729_085150_create_hrv_rmssd_data_table extends Migration
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
        $this->createTable('{{%hrv_rmssd_data}}', [
            'id'             => $this->primaryKey(),
            'user_id'        => $this->integer(11)->notNull(),
            'timestamp'      => $this->bigInteger(),
            'rmssd'          => $this->integer(),
            'low_frequency'  => $this->integer(),
            'high_frequency' => $this->integer(),
        ], $tableOptions);

        $this->createIndex('idx-rmssd_data-user_id', '{{%hrv_rmssd_data}}', 'user_id');

        $this->addForeignKey(
            'fk-rmssd_data-user_id',
            '{{%hrv_rmssd_data}}',
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
        $this->dropForeignKey('fk-rmssd_data-user_id', '{{%hrv_rmssd_data}}');
        $this->dropIndex('idx-rmssd_data-user_id', '{{%hrv_rmssd_data}}');
        $this->dropTable('{{%hrv_rmssd_data}}');
    }
}
