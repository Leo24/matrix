<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hrv_data".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $timestamp
 * @property double $start_rmssd
 * @property double $end_rmssd
 * @property double $total_recovery
 * @property double $recovery_ratio
 * @property double $recovery_rate
 *
 * @property Users $user
 */
class HrvData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hrv_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['timestamp'], 'safe'],
            [['start_rmssd', 'end_rmssd', 'total_recovery', 'recovery_ratio', 'recovery_rate'], 'number'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'timestamp' => 'Timestamp',
            'start_rmssd' => 'Start Rmssd',
            'end_rmssd' => 'End Rmssd',
            'total_recovery' => 'Total Recovery',
            'recovery_ratio' => 'Recovery Ratio',
            'recovery_rate' => 'Recovery Rate',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
}
