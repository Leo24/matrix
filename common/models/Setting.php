<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "settings".
 *
 * @property integer $user_id
 * @property string $timezome
 * @property string $timeformat
 * @property string $region
 * @property string $language
 * @property integer $text_size
 * @property string $app_sounds
 * @property integer $notifications
 * @property integer $progress_notifications
 * @property integer $experiment_notifications
 * @property integer $goal_notifications
 * @property integer $social_media_buttons_on_off
 * @property string $brightness
 */
class Setting extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'timezome', 'timeformat'], 'required'],
            [['user_id', 'text_size', 'notifications', 'progress_notifications', 'experiment_notifications', 'goal_notifications', 'social_media_buttons_on_off'], 'integer'],
            [['timezome', 'timeformat', 'region', 'language', 'app_sounds', 'brightness'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'timezome' => 'Timezome',
            'timeformat' => 'Timeformat',
            'region' => 'Region',
            'language' => 'Language',
            'text_size' => 'Text Size',
            'app_sounds' => 'App Sounds',
            'notifications' => 'Notifications',
            'progress_notifications' => 'Progress Notifications',
            'experiment_notifications' => 'Experiment Notifications',
            'goal_notifications' => 'Goal Notifications',
            'social_media_buttons_on_off' => 'Social Media Buttons On Off',
            'brightness' => 'Brightness',
        ];
    }
}
