<?php

use yii\db\Migration;

class m160728_112638_insert_test_user extends Migration
{
    public function up()
    {
        $this->insert('{{%user}}', [
            'id' => 1,
            'email' => 'test@test.com',
            'username' =>'Testname',
            'password' => Yii::$app->security->generatePasswordHash('test'),
        ]);

        $this->insert('{{%user}}', [
            'id' => 2,
            'email' => 'user@user.com',
            'username' =>'Username',
            'password' => Yii::$app->security->generatePasswordHash('user'),
        ]);
    }

    public function down()
    {
        $this->delete('{{%user}}', ['email' => 'test@test.com']);
        $this->delete('{{%user}}', ['email' => 'user@user.com']);
    }
}
