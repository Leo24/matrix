<?php

use yii\db\Migration;

/**
 * Handles the creation for table `health`.
 *
 * @author Dmitriy Sobolevskiy <d.sabaleuski@andersenlab.com>
 */
class m160810_122247_create_health_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $options = ($this->db->driverName === 'mysql')
            ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB' : null;

        $this->createTable('{{%health}}', [
            'id'                       => $this->primaryKey(),
            'user_id'                  => $this->integer(11)->unique()->notNull(),
            'weight'                   => $this->integer(3)->unsigned()->defaultValue(null),
            'height'                   => $this->decimal()->unsigned()->defaultValue(null),
            'blood_type'               => "enum('A','B', 'AB', 'O') DEFAULT NULL",
            'blood_pressure_systolic'  => $this->integer(3)->unsigned()->defaultValue(null),
            'blood_pressure_diastolic' => $this->integer(3)->unsigned()->defaultValue(null),
            'cholesterol_level'        => $this->integer(3)->unsigned()->defaultValue(null),
            'updated_at'               => $this->integer(11)->unsigned()->defaultValue(null),
        ], $options);

        $this->createIndex('idx-health-user_id', '{{%health}}', 'user_id');

        $this->addForeignKey(
            'fk-health-user_id',
            '{{%health}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-health-user_id', '{{%health}}');
        $this->dropIndex('idx-health-user_id', '{{%health}}');
        $this->dropTable('{{%health}}');
    }
}
