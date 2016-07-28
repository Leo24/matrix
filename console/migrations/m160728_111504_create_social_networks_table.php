<?php

use yii\db\Migration;
use yii\db\mysql\Schema;

/**
 * Handles the creation for table `social_networks`.
 */
class m160728_111504_create_social_networks_table extends Migration
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

        $this->createTable('{{%social_networks}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->notNull(),
            'social_network_type' => "enum('facebook','instagram','pinterest','twitter') NOT NULL",
            'data' => $this->text()->notNull(),
            'created_at' => Schema::TYPE_TIMESTAMP . ' DEFAULT CURRENT_TIMESTAMP',
        ], $tableOptions);

        $this->addForeignKey('fk_tbl_social_networks_tbl_users',
            '{{%social_networks}}', 'user_id',
            '{{%users}}', 'id',
            'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_tbl_social_networks_tbl_users', '{{%social_networks}}');
        $this->dropTable('{{%social_networks}}');
    }
}
