<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "calc_data".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $timestamp
 * @property double $heart_rate
 * @property double $respiration_rate
 * @property integer $activity
 *
 * @property Users $user
 */
class CalcData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'calc_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'activity'], 'integer'],
            [['timestamp'], 'integer'],
            [['heart_rate', 'respiration_rate'], 'number'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'heart_rate' => 'Heart Rate',
            'respiration_rate' => 'Respiration Rate',
            'activity' => 'Activity',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
