<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use Yii;

/**
 * Class ReasonUsingMatrix
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
            ['user_id', 'unique', 'targetClass' => self::className(),
                'message' => Yii::t('app', 'Sleeping position data exists')],
        ];
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

}