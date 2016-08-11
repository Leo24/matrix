<?php

use yii\db\Migration;

/**
 * Handles the creation for table `block`.
 *
 * @author Dmitriy Sobolevskiy <d.sabaleuski@andersenlab.com>
 */
class m160726_064733_create_block_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $options = ($this->db->driverName === 'mysql')
            ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB' : null;

        $this->createTable('{{%block}}', [
            'id'         => $this->primaryKey(),
            'token'      => $this->string(255)->unique(),
            'user_id'    => $this->integer(11)->unsigned()->defaultValue(null),
            'created_at' => $this->integer(11)->unsigned()->defaultValue(null),
            'expired_at' => $this->integer(11)->unsigned()->defaultValue(null),
        ], $options);

        $this->createIndex('idx-user-token', '{{%block}}', 'token');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropIndex('idx-user-token', '{{%block}}');
        $this->dropTable('{{%block}}');
    }
}
