<?php

use yii\db\Migration;

/**
 * Handles the creation for table `reason_using_matrix`.
 */
class m160801_121813_create_reason_using_matrix_table extends Migration
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

        $this->createTable('{{%reason_using_matrix}}', [
            'user_id' => $this->integer(11)->unique(),
            'overall_wellness' => $this->boolean()->defaultValue(false),
            'sleep_related_issues' => $this->boolean()->defaultValue(false),
            'specific_health_issues' => $this->boolean()->defaultValue(false),
            'athletic_training' => $this->boolean()->defaultValue(false),
            'other' => $this->boolean()->defaultValue(false),
            'updated_at' => $this->integer(11)->unsigned()->defaultValue(null),
            'PRIMARY KEY(user_id)'
        ], $tableOptions);

        $this->addForeignKey('fk_tbl_reason_using_matrix_tbl_user',
            '{{%reason_using_matrix}}', 'user_id',
            '{{%user}}', 'id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_tbl_reason_using_matrix_tbl_user', '{{%reason_using_matrix}}');
        $this->dropTable('{{%reason_using_matrix}}');
    }
}
