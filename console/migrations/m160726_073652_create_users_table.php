<?php

use yii\db\mysql\Schema;
use yii\db\Migration;

/**
 * Handles the creation for table `users`.
 */
class m160726_073652_create_users_table extends Migration
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

        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(11),
            'username' => $this->string(255)->notNull(),
            'password' => $this->string(255)->notNull(),
            'email' => $this->string(255)->notNull(),
            'created_at' => Schema::TYPE_TIMESTAMP . ' DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => Schema::TYPE_TIMESTAMP. ' NULL',
            'last_login' => Schema::TYPE_TIMESTAMP. ' NULL',
        ], $tableOptions);

        $this->insert('{{%users}}', [
            'email'=>'test@test.com',
            'username' =>'Testname',
            'password' => Yii::$app->security->generatePasswordHash('test'),
        ]);

        $this->createIndex('idx-users-username', '{{%users}}', 'username');
        $this->createIndex('idx-users-email', '{{%users}}', 'email');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropIndex('idx-users-username', '{{%users}}');
        $this->dropIndex('idx-users-email', '{{%users}}');

        $this->dropTable('{{%users}}');
    }
}
