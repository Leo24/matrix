<?php

use yii\db\Schema;
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
    public function up()
    {
        $options = ($this->db->driverName === 'mysql')
            ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB' : null;

        $this->createTable('{{%block}}', [
            'token'      => $this->string(255),
            'user_id'    => $this->integer(11)->unsigned()->defaultValue(null),
            'created_at' => $this->integer(11)->unsigned()->defaultValue(null),
            'expired_at' => $this->integer(11)->unsigned()->defaultValue(null),
            'PRIMARY KEY(token)'
        ], $options);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%block}}');
    }
}
