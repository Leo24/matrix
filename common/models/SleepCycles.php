<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sleep_cycles".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $date
 * @property string $timestamp
 * @property string $sleep_epoch_datapoint
 * @property integer $sleep_duration
 * @property integer $time_to_fall_asleep
 */
class SleepCycles extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sleep_cycles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'sleep_duration', 'time_to_fall_asleep'], 'integer'],
            [['date', 'timestamp'], 'safe'],
            [['sleep_epoch_datapoint'], 'string'],
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
            'date' => 'Date',
            'timestamp' => 'Timestamp',
            'sleep_epoch_datapoint' => 'Sleep Epoch Datapoint',
            'sleep_duration' => 'Sleep Duration',
            'time_to_fall_asleep' => 'Time To Fall Asleep',
        ];
    }
}
