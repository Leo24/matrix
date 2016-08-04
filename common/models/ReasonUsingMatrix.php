<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "reason_using_matrix".
 *
 * @package common\models
 */
class ReasonUsingMatrix extends ActiveRecord
{
    /**
     * Table name
     * @return string
     */
    public static function tableName()
    {
        return '{{%reason_using_matrix}}';
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            'overall_wellness',
            'sleep_related_issues',
            'specific_health_issues',
            'athletic_training',
            'other',
            'updated_at',
        ];
    }

    /**
     * @inheritdoc
     */
    public function extraFields()
    {
        return ['user'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => null,
                'updatedAtAttribute' => 'updated_at',
                'value' => function () {
                    return time();
                },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'overall_wellness',
                    'sleep_related_issues',
                    'specific_health_issues',
                    'athletic_training',
                    'other'
                ],
                'boolean'
            ],
            [
                [
                    'overall_wellness',
                    'sleep_related_issues',
                    'specific_health_issues',
                    'athletic_training',
                    'other'
                ],
                'safe'
            ],
            [
                'user_id',
                'unique',
                'targetClass' => self::className(),
                'message' => Yii::t('app', 'Sleeping position data exists')
            ],
        ];
    }
}
