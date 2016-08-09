<?php

use yii\db\Migration;

/**
 * Handles the creation for table `settings_notification`.
 */
class m160809_065149_create_settings_notification_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('{{%setting_notification}}', [
            'id'           => $this->primaryKey(),
            'user_id'      => $this->integer(11)->notNull(),
            'general'      => $this->integer(1)->defaultValue(1),
            'banner'       => $this->integer(1)->defaultValue(1),
            'preview_text' => $this->integer(1)->defaultValue(1),
            'alert_sound'  => $this->integer(1)->defaultValue(1),
            'vibrate'      => $this->integer(1)->defaultValue(1),
            'email'        => $this->integer(1)->defaultValue(1),
            'created_at'   => $this->integer(11)->notNull(),
            'updated_at'   => $this->integer(11)->notNull()
        ]);

        $this->createIndex('idx-setting_notification-user_id', '{{%setting_notification}}', 'user_id');
        $this->addForeignKey('fk-setting_notification-user_id', '{{%setting_notification}}', 'user_id', 'user', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%setting_notification}}');
        $this->dropIndex('idx-setting_notification-user_id', '{{%setting_notification}}');
        $this->dropForeignKey('fk-setting_notification-user_id', '{{%setting_notification}}');
    }
}
