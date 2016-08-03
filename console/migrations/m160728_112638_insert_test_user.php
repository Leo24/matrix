<?php

use yii\db\Migration;

class m160728_112638_insert_test_user extends Migration
{
    public function up()
    {
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
