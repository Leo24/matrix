<?php

use yii\db\Migration;

/**
 * Handles the creation for table `accomplishment`.
 */
class m160809_110237_create_accomplishment_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $options = ($this->db->driverName === 'mysql')
            ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB' : null;

        $this->createTable('{{%accomplishment}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->unique(),
            'title' => $this->string(255),
            'created_at' => $this->integer(11)->unsigned()->defaultValue(null),
            'updated_at' => $this->integer(11)->unsigned()->defaultValue(null),
        ], $options);

        $this->createIndex('idx-accomplishment-user-id', '{{%accomplishment}}', 'user_id');

        $this->addForeignKey(
            'fk_tbl_accomplishment_tbl_user',
            '{{%accomplishment}}',
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
        $this->dropIndex('idx-accomplishment-user-id', '{{%accomplishment}}');
        $this->dropForeignKey('fk_tbl_accomplishment_tbl_user', '{{%accomplishment}}');
        $this->dropTable('{{%accomplishment}}');
    }
}
