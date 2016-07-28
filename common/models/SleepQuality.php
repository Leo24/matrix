<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sleep_quality".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $date
 * @property integer $sleep_class_awake_percent
 * @property integer $sleep_class_deep_percent
 * @property integer $sleep_class_light_percent
 * @property integer $sleep_class_rem_percent
 * @property integer $sleep_class_awake_duration
 * @property integer $sleep_class_deep_duration
 * @property integer $sleep_class_light_duration
 * @property integer $sleep_class_rem_duration
 */
class SleepQuality extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sleep_quality';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'sleep_class_awake_percent', 'sleep_class_deep_percent', 'sleep_class_light_percent', 'sleep_class_rem_percent', 'sleep_class_awake_duration', 'sleep_class_deep_duration', 'sleep_class_light_duration', 'sleep_class_rem_duration'], 'integer'],
            [['date'], 'safe'],
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
            'sleep_class_awake_percent' => 'Sleep Class Awake Percent',
            'sleep_class_deep_percent' => 'Sleep Class Deep Percent',
            'sleep_class_light_percent' => 'Sleep Class Light Percent',
            'sleep_class_rem_percent' => 'Sleep Class Rem Percent',
            'sleep_class_awake_duration' => 'Sleep Class Awake Duration',
            'sleep_class_deep_duration' => 'Sleep Class Deep Duration',
            'sleep_class_light_duration' => 'Sleep Class Light Duration',
            'sleep_class_rem_duration' => 'Sleep Class Rem Duration',
        ];
    }
}
