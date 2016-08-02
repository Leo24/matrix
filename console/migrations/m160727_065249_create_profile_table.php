<?php

use yii\db\mysql\Schema;
use yii\db\Migration;

/**
 * Handles the creation for table `profile`.
 */
class m160727_065249_create_profile_table extends Migration
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

        $this->createTable('{{%profile}}', [
            'user_id' => $this->integer(11)->notNull(),
            'firstname' => $this->string(30)->notNull(),
            'lastname' => $this->string(30)->notNull(),
            'gender' => "enum('female','male') NOT NULL DEFAULT 'male'",
            'state' => $this->string(20)->notNull(),
            'city' => $this->string(20)->notNull(),
            'profession_interest' => $this->string(255)->notNull(),
            'average_hours_sleep' => $this->string(255),
            'device_name' => $this->string(255),
            'device_position' => "enum('left','right','middle') DEFAULT NULL",
            'updated_at' => $this->integer(11)->unsigned()->defaultValue(null),
            'PRIMARY KEY(user_id)'
        ], $tableOptions);

        $this->addForeignKey('fk_tbl_profile_tbl_user',
            '{{%profile}}', 'user_id',
            '{{%user}}', 'id',
            'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_tbl_profile_tbl_user', '{{%profile}}');
        $this->dropTable('{{%profile}}');
    }
}
