<?php

use yii\db\Migration;

class m160802_074416_notification_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%notification}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11),
            'created_at' => $this->integer(11)->unsigned()->defaultValue(null),
            'title' => $this->string(255),
            'description' => $this->text()->defaultValue(null),
            'viewed'=>$this->boolean()->defaultValue(false),
            'type' => $this->string(255)->defaultValue(null),
            'tag' => $this->string(255)->defaultValue(null),
        ], $tableOptions);

        $this->addForeignKey('fk_tbl_notification_tbl_user',
            '{{%notification}}', 'user_id',
            '{{%user}}', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_tbl_notification_tbl_user', '{{%notification}}');
        $this->dropTable('{{%notification}}');
    }
}
