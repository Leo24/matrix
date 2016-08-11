<?php

use yii\db\Migration;

class m160802_074416_create_notification_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = ($this->db->driverName === 'mysql')
            ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB' : null;

        $this->createTable('{{%notification}}', [
            'id'          => $this->primaryKey(),
            'user_id'     => $this->integer(11)->notNull(),
            'title'       => $this->string(255)->notNull(),
            'description' => $this->text()->defaultValue(null),
            'viewed'      => $this->boolean()->defaultValue(false),
            'type'        => "ENUM('positivity', 'negativity', 'neutrality') NOT NULL DEFAULT 'neutrality'",
            'tag'         => "ENUM('report', 'experiment', 'tip', 'matrix', 'goal') NOT NULL DEFAULT 'report'",
            'created_at'  => $this->integer(11)->unsigned()->defaultValue(null),
            'updated_at'  => $this->integer(11)->unsigned()->defaultValue(null),
        ], $tableOptions);

        $this->createIndex('idx_notification_user_id', '{{%notification}}', 'user_id');

        $this->addForeignKey(
            'fk_tbl_notification_tbl_user',
            '{{%notification}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_tbl_notification_tbl_user', '{{%notification}}');
        $this->dropIndex('idx_notification_user_id', '{{%notification}}');
        $this->dropTable('{{%notification}}');
    }
}
