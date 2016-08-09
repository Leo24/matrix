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
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->notNull(),
            'general' => $this->boolean()->defaultValue(true),
            'banner' => $this->boolean()->defaultValue(true),
            'preview_text' => $this->boolean()->defaultValue(true),
            'alert_sound' => $this->boolean()->defaultValue(true),
            'vibrate' => $this->boolean()->defaultValue(true),
            'email' => $this->boolean()->defaultValue(true),
            'created_at' => $this->integer(11)->unsigned()->notNull(),
            'updated_at' => $this->integer(11)->unsigned()->defaultValue(null),
        ]);

        $this->createIndex('idx-setting_notification-user_id', '{{%setting_notification}}', 'user_id');
        $this->addForeignKey('fk-setting_notification-user_id', '{{%setting_notification}}', 'user_id', 'user', 'id',
            'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-setting_notification-user_id', '{{%setting_notification}}');
        $this->dropIndex('idx-setting_notification-user_id', '{{%setting_notification}}');
        $this->dropTable('{{%setting_notification}}');
    }
}
