<?php

namespace common\models;

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

    /**
     * Primary key name
     *
     * @inheritdoc
     */
    public $primaryKey = 'user_id';

    /**
     * Table name
     *
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%profile}}';
    }

    /**
     * Attribute labels
     *
     * @inheritdoc
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
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarion = parent::scenarios();
        $scenarion[self::SCENARIO_REGISTER] = [
                'firstname', 'lastname', 'gender', 'state', 'city', 'profession_interest',
                'average_hours_sleep','user_id', 'average_hours_sleep', 'device_name', 'device_position'
            ];
        return $scenarion;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['firstname', 'lastname', 'profession_interest', 'state', 'city'], 'trim'],
            [['firstname', 'lastname', 'state', 'city', 'profession_interest'], 'required', 'on' => 'register'],
            [['firstname', 'lastname'], 'string', 'max' => 30],
            [['city', 'state'], 'string', 'max' => 20],
            [['profession_interest', 'average_hours_sleep', 'device_name'], 'string', 'max' => 255],
            ['gender', 'in', 'range' => ['female', 'male']],
            ['device_position', 'in', 'range' => ['left','right','middle']],
            ['user_id', 'unique', 'targetClass' => self::className(), 'message' => Yii::t('app', 'Profile exists')],
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
        return $this->hasOne(User::className(), ['id' => 'user_id']);
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