<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Handles the creation for table `profiles`.
 */
class m160729_083033_create_calc_data_table extends Migration
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
        $this->createTable('{{%calc_data}}', [
            'id'               => $this->primaryKey(),
            'user_id'          => $this->integer(11)->notNull(),
            'timestamp'        => $this->bigInteger(),
            'heart_rate'       => $this->float(),
            'respiration_rate' => $this->float(),
            'activity'         => $this->integer(),
            'created_at'       => $this->integer(11)->unsigned()->defaultValue(null),
            'updated_at'       => $this->integer(11)->unsigned()->defaultValue(null),
        ], $tableOptions);

        $this->createIndex('idx_calc_data_user_id', '{{%calc_data}}', 'user_id');

        $this->addForeignKey(
            'fk_tbl_calc_data_tbl_user',
            '{{%calc_data}}',
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
        $this->dropForeignKey('fk_tbl_calc_data_tbl_user', '{{%calc_data}}');
        $this->dropIndex('idx_calc_data_user_id', '{{%calc_data}}');
        $this->dropTable('{{%calc_data}}');
    }
}