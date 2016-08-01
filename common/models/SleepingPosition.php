<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use Yii;

class SleepingPosition extends ActiveRecord
{

    /**
     * Table name
     * @return string
     */
    public static function tableName()
    {
        return '{{%sleeping_position}}';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['back_sleeper', 'side_sleeper', 'stomach_sleeper'], 'boolean'],
            [['back_sleeper', 'side_sleeper', 'stomach_sleeper'], 'safe'],
            ['user_id', 'unique', 'targetClass' => self::className(), 'message' => Yii::t('app', 'Sleeping position data exists')],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

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