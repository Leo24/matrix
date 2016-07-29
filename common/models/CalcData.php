<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "calc_data".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $timestamp
 * @property integer $rmssd
 * @property integer $low_frequency
 * @property integer $high_frequency
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
            [['user_id', 'rmssd', 'low_frequency', 'high_frequency'], 'integer'],
            [['timestamp'], 'safe'],
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
            'rmssd' => 'Rmssd',
            'low_frequency' => 'Low Frequency',
            'high_frequency' => 'High Frequency',
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
