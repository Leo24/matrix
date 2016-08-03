<?php

use yii\db\Migration;
use yii\db\mysql\Schema;

/**
 * Handles the creation for table `social_network`.
 */
class m160728_111504_create_social_network_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $options = ($this->db->driverName === 'mysql')
            ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB' : null;

        $this->createTable('{{%social_network}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->notNull(),
            'social_network_type' => "enum('facebook','instagram','pinterest','twitter') NOT NULL",
            'data' => $this->text()->notNull(),
            'created_at' => $this->integer(11)->unsigned()->defaultValue(null),
        ], $options);

        $this->addForeignKey('fk_tbl_social_network_tbl_user',
            '{{%social_network}}', 'user_id',
            '{{%user}}', 'id',
            'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_tbl_social_network_tbl_user', '{{%social_network}}');
        $this->dropTable('{{%social_network}}');
    }
}
