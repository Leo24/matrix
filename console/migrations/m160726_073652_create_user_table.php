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
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(11),
            'username' => $this->string(255)->notNull(),
            'password' => $this->string(255)->notNull(),
            'email' => $this->string(255)->unique()->notNull(),
            'created_at' => $this->integer(11)->unsigned()->defaultValue(null),
            'updated_at' => $this->integer(11)->unsigned()->defaultValue(null),
            'last_login' => $this->integer(11)->unsigned()->defaultValue(null),
        ], $tableOptions);

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
