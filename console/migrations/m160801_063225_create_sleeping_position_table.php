<?php

use yii\db\Migration;

/**
 * Handles the creation for table `sleeping_position`.
 *
 * @author Dmitriy Sobolevskiy <d.sabaleuski@andersenlab.com>
 */
class m160801_063225_create_sleeping_position_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $options = ($this->db->driverName === 'mysql')
            ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB' : null;

        $this->createTable('{{%sleeping_position}}', [
            'id'              => $this->primaryKey(),
            'user_id'         => $this->integer(11)->unique(),
            'back_sleeper'    => $this->boolean()->defaultValue(false),
            'side_sleeper'    => $this->boolean()->defaultValue(false),
            'stomach_sleeper' => $this->boolean()->defaultValue(false),
            'updated_at'      => $this->integer(11)->unsigned()->defaultValue(null),
        ], $options);

        $this->createIndex('idx-sleeping-position-user-id', '{{%sleeping_position}}', 'user_id');

        $this->addForeignKey(
            'fk_tbl_sleeping_position_tbl_user',
            '{{%sleeping_position}}',
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
        $this->dropForeignKey('fk_tbl_sleeping_position_tbl_user', '{{%sleeping_position}}');
        $this->dropIndex('idx-sleeping-position-user-id', '{{%sleeping_position}}');
        $this->dropTable('{{%sleeping_position}}');
    }
}
