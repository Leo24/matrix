<?php

use yii\db\Migration;

/**
 * Handles the creation for table `device`.
 */
class m160803_074023_create_device_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $options = ($this->db->driverName === 'mysql')
            ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB' : null;

        $this->createTable('{{%device}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->unique(),
            'name' => $this->string(255),
            'position' => "enum('left','right','middle') DEFAULT NULL",
            'pin' => $this->string(255)->notNull(),
            'pw' => $this->string(255)->notNull(),
            'sn' => $this->string(255)->notNull(),
            'updated_at' => $this->integer(11)->unsigned()->defaultValue(null),
        ], $options);

        $this->createIndex('idx-device-user-id', '{{%device}}', 'user_id');

        $this->addForeignKey('fk_tbl_device_tbl_user',
            '{{%device}}', 'user_id',
            '{{%user}}', 'id',
            'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropIndex('idx-device-user-id', '{{%device}}');
        $this->dropForeignKey('fk_tbl_device_tbl_user', '{{%device}}');
        $this->dropTable('{{%device}}');
    }
}
