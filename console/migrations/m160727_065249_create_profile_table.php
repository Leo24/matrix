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
        $options = ($this->db->driverName === 'mysql')
            ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB' : null;

        $this->createTable('{{%profile}}', [
            'user_id' => $this->integer(11)->notNull(),
            'firstname' => $this->string(30)->notNull(),
            'lastname' => $this->string(30)->notNull(),
            'gender' => "enum('female','male') NOT NULL DEFAULT 'male'",
            'avatar_url' => $this->string(255)->defaultValue(null),
            'state' => $this->string(20)->notNull(),
            'city' => $this->string(20)->notNull(),
            'phone' => $this->string(20)->defaultValue(null),
            'birthday' => $this->integer(11)->unsigned()->notNull(),
            'profession_interest' => $this->string(255)->notNull(),
            'average_hours_sleep' => $this->string(255),
            'updated_at' => $this->integer(11)->unsigned()->defaultValue(null),
            'PRIMARY KEY(user_id)'
        ], $options);

        $this->createIndex('idx-profile-user-id', '{{%profile}}', 'user_id');

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
        $this->dropIndex('idx-profile-user-id', '{{%profile}}');
        $this->dropForeignKey('fk_tbl_profile_tbl_user', '{{%profile}}');
        $this->dropTable('{{%profile}}');
    }
}
