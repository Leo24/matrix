<?php

namespace common\modules\api\v1\health\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use common\modules\api\v1\user\models\User;

/**
 * This is the model class for table 'health'
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $weight
 * @property float $height
 * @property string $blood_type
 * @property integer $blood_pressure_systolic
 * @property integer $blood_pressure_diastolic
 * @property integer $cholesterol_level
 * @property integer $updated_at
 *
 * @author Dmitriy Sobolevskiy <d.sabaleuski@andersenlab.com>
 * @package common\modules\api\v1\health\models
 */
class Health extends ActiveRecord
{
    /**
     * Table name
     *
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%health}}';
    }

    /**
     * Attribute labels
     *
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                       => Yii::t('app', 'Id'),
            'user_id'                  => Yii::t('app', 'User Id'),
            'weight'                   => Yii::t('app', 'Weight'),
            'height'                   => Yii::t('app', 'Height'),
            'blood_type'               => Yii::t('app', 'Blood type'),
            'blood_pressure_systolic'  => Yii::t('app', 'Blood pressure (systolic)'),
            'blood_pressure_diastolic' => Yii::t('app', 'Blood pressure (diastolic)'),
            'cholesterol_level'        => Yii::t('app', 'Cholesterol level'),
            'updated_at'               => Yii::t('app', 'Updated_at'),
        ];
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
            [
                [
                    'user_id',
                    'weight',
                    'blood_pressure_systolic',
                    'blood_pressure_diastolic',
                    'cholesterol_level'
                ],
                'integer'
            ],
            [['user_id'], 'required'],
            [['blood_type'], 'string', 'max' => 3],
            ['user_id', 'unique', 'targetClass' => self::className(), 'message' => Yii::t('app', 'User Id exists')],
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
