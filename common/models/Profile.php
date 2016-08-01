<?php

namespace common\models;

use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use Yii;

/**
 * Class Profile
 * @package common\models
 */
class Profile extends ActiveRecord
{

    public $sleeping_position;

    const SCENARIO_REGISTER = 'register';

    public $primaryKey = 'user_id';

    /**
     * Table name
     * @return string
     */
    public static function tableName()
    {
        return '{{%profile}}';
    }

    /**
     * Attribute labels
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'firstname' => 'First name',
            'lastname' => 'Last name',
            'gender' => 'Gender',
            'state' => 'State',
            'city' => 'City',
            'profession_interest' => 'Profession interest',
        ];
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        $scenarion = parent::scenarios();
        $scenarion[self::SCENARIO_REGISTER] = [
                'firstname', 'lastname', 'gender', 'state', 'city', 'profession_interest',
                'average_hours_sleep','user_id', 'average_hours_sleep'
            ];
        return $scenarion;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['firstname', 'lastname', 'profession_interest', 'state', 'city'], 'trim'],
            [['firstname', 'lastname', 'state', 'city', 'profession_interest'], 'required', 'on' => 'register'],
            [['firstname', 'lastname'], 'string', 'max' => 30],
            [['city', 'state'], 'string', 'max' => 20],
            [['profession_interest', 'average_hours_sleep'], 'string', 'max' => 255],
            ['gender', 'in', 'range' => ['female', 'male']],
            ['user_id', 'unique', 'targetClass' => self::className(), 'message' => Yii::t('app', 'Profile exists')],
        ];
    }

    /**
     * @return array
     */
    public function fields()
    {
        $fields = parent::fields();
//        $fields['sleeping_position'] = function($model) {
//            return $model->getSleepingPosition();
//        };
//        $fields['reason_using_matrix'] = function($model) {
//            return $model->getReasonUsingMatrix();
//        };
//        $fields['sleeping_positions'] = function($model) {
//            return $model->getSleepingPosition();
//        };

        return $fields;
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSleepingPosition()
    {
        return $this->hasOne(SleepingPosition::className(), ['id' => 'profile_id']);
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