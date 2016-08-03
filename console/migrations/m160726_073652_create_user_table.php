<?php

use yii\db\mysql\Schema;
use yii\db\Migration;

/**
 * Handles the creation for table `user`.
 */
class m160726_073652_create_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $options = ($this->db->driverName === 'mysql')
            ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB' : null;

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(11),
            'username' => $this->string(255)->notNull(),
            'password' => $this->string(255)->notNull(),
            'email' => $this->string(255)->unique()->notNull(),
            'created_at' => $this->integer(11)->unsigned()->defaultValue(null),
            'updated_at' => $this->integer(11)->unsigned()->defaultValue(null),
            'last_login' => $this->integer(11)->unsigned()->defaultValue(null),
        ], $options);

        $this->createIndex('idx-user-username', '{{%user}}', 'username');
        $this->createIndex('idx-user-email', '{{%user}}', 'email');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropIndex('idx-user-username', '{{%user}}');
        $this->dropIndex('idx-user-email', '{{%user}}');

        $this->dropTable('{{%user}}');
    }
}
