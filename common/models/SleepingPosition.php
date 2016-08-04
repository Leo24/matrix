<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class SleepingPosition extends ActiveRecord
{
    /**
     * Table name
     * @inheritdoc string
     */
    public static function tableName()
    {
        return '{{%sleeping_position}}';
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
            [['back_sleeper', 'side_sleeper', 'stomach_sleeper'], 'boolean'],
            [['back_sleeper', 'side_sleeper', 'stomach_sleeper'], 'safe'],
            [
                'user_id',
                'unique',
                'targetClass' => self::className(),
                'message' => Yii::t('app', 'Sleeping position data exists')
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
