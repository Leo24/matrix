<?php

use yii\db\Migration;

/**
 * Handles the creation for table `movement`.
 */
class m160812_103755_create_movement_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('movement', [
            'id'         => $this->primaryKey(),
            'user_id'    => $this->integer(11)->notNull(),
            'timestamp'  => $this->integer(11)->notNull(),
            'created_at' => $this->integer(11)->unsigned()->defaultValue(null),
            'updated_at' => $this->integer(11)->unsigned()->defaultValue(null)
        ]);

        $this->createIndex('idx_movement_user_id', '{{%movement}}', 'user_id');

        $this->addForeignKey(
            'fk_tbl_movement_tbl_user',
            '{{%movement}}',
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
        $this->dropForeignKey('fk_tbl_movement_tbl_user', '{{%movement}}');
        $this->dropIndex('idx_movement_user_id', '{{%movement}}');
        $this->dropTable('{{%movement}}');
    }
}
