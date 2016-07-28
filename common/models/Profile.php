<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use Yii;

/**
 * Class Profile
 * @package common\modules
 */
class Profile extends ActiveRecord
{

    const SCENARIO_REGISTER = 'register';

    public $primaryKey = 'user_id';

    /**
     * Table name
     * @return string
     */
    public static function tableName()
    {
        return '{{%profiles}}';
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
            'sleeping_position' => 'Sleeping position',
            'average_hours_sleep' => 'Average hours sleep',
        ];
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_REGISTER => [
                'firstname', 'lastname', 'gender', 'state', 'city', 'profession_interest',
                'sleeping_position', 'average_hours_sleep', 'reason_using_matrix', 'user_id'
            ],
        ];
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
            [['profession_interest'], 'string', 'max' => 255],
            ['gender', 'in', 'range' => ['female', 'male']],
            ['user_id', 'unique', 'targetClass' => self::className(), 'message' => Yii::t('app', 'Profile exists')],
        ];
    }

    public function fields()
    {
        $fields = parent::fields();
        $fields['sleeping_position'] = function($model) {
            return $model->getSleepingPosition();
        };
        $fields['reason_using_matrix'] = function($model) {
            return $model->getReasonUsingMatrix();
        };

        if($this->scenario == self::SCENARIO_REGISTER) {
            unset($fields['user_id']);
        }

        return $fields;
    }

    public function getSleepingPosition()
    {
        return json_decode($this->sleeping_position);
    }

    public function getReasonUsingMatrix()
    {
        return json_decode($this->reason_using_matrix);
    }

//    /**
//     *
//     */
//    public function afterFind()
//    {
//        $this->sleeping_position = getSleepingPosition();
//        $this->reason_using_matrix = json_decode($this->reason_using_matrix);
//
//        return parent::afterFind();
//    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->sleeping_position = json_encode($this->sleeping_position);
            $this->reason_using_matrix = json_encode($this->reason_using_matrix);
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => function () {
                    return date('Y-m-d H:i:s');
                },
            ],
        ];
    }

}