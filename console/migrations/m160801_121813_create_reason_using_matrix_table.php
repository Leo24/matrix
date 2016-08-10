<?php

use yii\db\Migration;

/**
 * Handles the creation for table `reason_using_matrix`.
 *
 * @author Dmitriy Sobolevskiy <d.sabaleuski@andersenlab.com>
 */
class m160801_121813_create_reason_using_matrix_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $options = ($this->db->driverName === 'mysql')
            ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB' : null;

        $this->createTable('{{%reason_using_matrix}}', [
            'user_id'                => $this->integer(11)->unique(),
            'overall_wellness'       => $this->boolean()->defaultValue(false),
            'sleep_related_issues'   => $this->boolean()->defaultValue(false),
            'specific_health_issues' => $this->boolean()->defaultValue(false),
            'athletic_training'      => $this->boolean()->defaultValue(false),
            'other'                  => $this->boolean()->defaultValue(false),
            'updated_at'             => $this->integer(11)->unsigned()->defaultValue(null),
            'PRIMARY KEY(user_id)'
        ], $options);

        $this->createIndex('idx-reason-using-matrix-user-id', '{{%reason_using_matrix}}', 'user_id');

        $this->addForeignKey(
            'fk_tbl_reason_using_matrix_tbl_user',
            '{{%reason_using_matrix}}',
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
        $this->dropForeignKey('fk_tbl_reason_using_matrix_tbl_user', '{{%reason_using_matrix}}');
        $this->dropIndex('idx-reason-using-matrix-user-id', '{{%reason_using_matrix}}');
        $this->dropTable('{{%reason_using_matrix}}');
    }
}
