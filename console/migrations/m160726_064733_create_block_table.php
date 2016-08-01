<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Handles the creation for table `block`.
 */
class m160726_064733_create_block_table extends Migration
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

        $this->createTable('{{%block}}', [
            'token' => $this->string(255),
            'user_id' => $this->integer(11)->unsigned()->defaultValue(null),
            'created_at' => $this->integer(11)->unsigned()->defaultValue(null),
            'expired_at' => $this->integer(11)->unsigned()->defaultValue(null),
            'PRIMARY KEY(token)'
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%block}}');
    }
}
