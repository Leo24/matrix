<?php

use yii\db\mysql\Schema;
use yii\db\Migration;

/**
 * Handles the creation for table `profiles`.
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

        $this->createTable('{{%profiles}}', [
            'user_id' => $this->integer(11)->notNull(),
            'firstname' => $this->string(30)->notNull(),
            'lastname' => $this->string(30)->notNull(),
            'gender' => "enum('female','male') NOT NULL DEFAULT 'male'",
            'state_id' => $this->integer(11)->notNull(),
            'city_id' => $this->integer(11)->notNull(),
            'profession_interest' => $this->string(255)->notNull(),
            'sleeping_position' => $this->text(),
            'average_hours_sleep' => $this->integer(11),
            'reason_using_matrix' => $this->text(),
            'updated_at' => Schema::TYPE_TIMESTAMP. ' NULL',
            'PRIMARY KEY(user_id)'
        ], $tableOptions);

        $this->addForeignKey('fk_tbl_profiles_tbl_users',
            '{{%profiles}}', 'user_id',
            '{{%users}}', 'id',
            'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_tbl_profiles_tbl_users', '{{%profiles}}');
        $this->dropTable('{{%profiles}}');
    }
}
