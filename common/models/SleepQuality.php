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
     * Primary key name
     *
     * @inheritdoc
     */
    public $primaryKey = 'id';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sleep_quality}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'date', 'sleep_score', 'duration', 'duration_in_bed', 'duration_awake', 'duration_in_sleep', 'duration_in_rem', 'duration_in_light', 'duration_in_deep', 'duration_sleep_onset', 'bedexit_duration', 'bedexit_count', 'tossnturn_count', 'fm_count', 'awakenings'], 'integer'],
            [['from', 'to'], 'string'],
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
            'from' => 'From',
            'to' => 'To',
            'date' => 'Date',
            'sleep_score' => 'Sleep Score',
            'duration' => 'Duration',
            'duration_in_bed' => 'Duration in Bed',
            'duration_awake' => 'Duration Awake',
            'duration_in_sleep' => 'Duration in Sleep',
            'duration_in_rem' => 'Duration in REM',
            'duration_in_light' => 'Duration in Light',
            'duration_in_deep' => 'Duration in Deep',
            'duration_sleep_onset' => 'Duration Sleep onset',
            'bedexit_duration' => 'Bedexit Duration',
            'bedexit_count' => 'Bedexit Count',
            'tossnturn_count' => 'Tossnturn Count',
            'fm_count' => 'FM Count',
            'awakenings' => 'Awakenings',

        ];
    }
}
