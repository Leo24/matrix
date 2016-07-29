<?php
use yii\db\Schema;
use yii\db\Migration;
/**
 * Handles the creation for table `profiles`.
 */
class m160729_083033_calc_data_table extends Migration
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
        $this->createTable('{{%calc_data}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->notNull(),
            'timestamp' => $this->bigInteger(),
            'heart_rate' => $this->float(),
            'respiration_rate' => $this->float(),
            'activity' => $this->integer(),
        ], $tableOptions);
        $this->addForeignKey('fk_tbl_calc_data_tbl_users',
            '{{%calc_data}}', 'user_id',
            '{{%users}}', 'id',
            'CASCADE', 'CASCADE');
    }
    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_tbl_calc_data_tbl_users', '{{%calc_data}}');
        $this->dropTable('{{%calc_data}}');
    }
}