<?php

use yii\db\Migration;

class m160728_112638_insert_test_user extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->insert('{{%user}}', [
            'email' => 'test@test.com',
            'username' =>'Testname',
            'password' => Yii::$app->security->generatePasswordHash('test'),
        ]);
    }

    public function down()
    {
        $this->delete('{{%user}}', ['email' => 'test@test.com']);
    }
}
