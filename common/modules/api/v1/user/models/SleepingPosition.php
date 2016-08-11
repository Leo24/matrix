<?php

namespace common\modules\api\v1\user\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table 'sleeping_position'
 *
 * @property integer $user_id
 * @property boolean $back_sleeper
 * @property boolean $side_sleeper
 * @property boolean $stomach_sleeper
 * @property integer $updated_at
 *
 * @author Dmitriy Sobolevskiy <d.sabaleuski@andersenlab.com>
 * @package common\modules\api\v1\user\models
 */
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
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => null,
                'updatedAtAttribute' => 'updated_at',
                'value'              => function () {
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
                'message'     => Yii::t('app', 'Sleeping position data exists')
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
