<?php

use yii\db\Migration;

/**
 * Handles the creation for table `sleep_quality_table`.
 */
class m160805_132257_create_sleep_quality_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%sleep_quality}}', [
            'id'                   => $this->primaryKey(),
            'user_id'              => $this->integer(11),
            'from'                 => $this->string(255),
            'to'                   => $this->string(255),
            'timestamp'            => $this->integer(14),
            'sleep_score'          => $this->integer(11),
            'duration'             => $this->integer(11),
            'duration_in_bed'      => $this->integer(11),
            'duration_awake'       => $this->integer(11),
            'duration_in_sleep'    => $this->integer(11),
            'duration_in_rem'      => $this->integer(11),
            'duration_in_light'    => $this->integer(11),
            'duration_in_deep'     => $this->integer(11),
            'duration_sleep_onset' => $this->integer(11),
            'bedexit_duration'     => $this->integer(11),
            'bedexit_count'        => $this->integer(11),
            'tossnturn_count'      => $this->integer(11),
            'fm_count'             => $this->integer(11),
            'awakenings'           => $this->integer(11),
            'avg_hr'               => $this->float(),
            'avg_rr'               => $this->float(),
            'avg_act'              => $this->float(),
            'min_hr'               => $this->integer(11),
            'max_hr'               => $this->integer(11),
            'min_rr'               => $this->integer(11),
            'max_rr'               => $this->integer(11),
            'created_at'           => $this->integer(11)->unsigned()->defaultValue(null),
            'updated_at'           => $this->integer(11)->unsigned()->defaultValue(null),
        ], $tableOptions);

        $this->createIndex('idx_sleep_quality_user_id', '{{%sleep_quality}}', 'user_id');

        $this->addForeignKey(
            'fk_tbl_sleep_quality_tbl_user',
            '{{%sleep_quality}}',
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
        $this->dropForeignKey('fk_tbl_sleep_quality_tbl_user', '{{%sleep_quality}}');
        $this->dropIndex('idx_sleep_quality_user_id', '{{%sleep_quality}}');
        $this->dropTable('{{%sleep_quality}}');
    }
}
