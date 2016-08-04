<?php

use yii\db\Migration;

/**
 * Handles the creation for table `sleeping_position`.
 */
class m160801_063225_create_sleeping_position_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $options = ($this->db->driverName === 'mysql')
            ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB' : null;

        $this->createTable('{{%sleeping_position}}', [
            'user_id' => $this->integer(11)->unique(),
            'back_sleeper' => $this->boolean()->defaultValue(false),
            'side_sleeper' => $this->boolean()->defaultValue(false),
            'stomach_sleeper' => $this->boolean()->defaultValue(false),
            'updated_at' => $this->integer(11)->unsigned()->defaultValue(null),
            'PRIMARY KEY(user_id)'
        ], $options);

        $this->createIndex('idx-sleeping-position-user-id', '{{%sleeping_position}}', 'user_id');

        $this->addForeignKey('fk_tbl_sleeping_position_tbl_user',
            '{{%sleeping_position}}', 'user_id',
            '{{%user}}', 'id',
            'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropIndex('idx-sleeping-position-user-id', '{{%sleeping_position}}');
        $this->dropForeignKey('fk_tbl_sleeping_position_tbl_user', '{{%sleeping_position}}');
        $this->dropTable('{{%sleeping_position}}');
    }
}
