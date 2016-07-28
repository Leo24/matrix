<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "profiles".
 *
 * @property integer $users_id
 * @property string $firstname
 * @property string $lastname
 * @property string $birthday
 * @property string $gender
 * @property string $city
 * @property string $occupation
 * @property string $sleeping_position
 * @property integer $weight
 * @property integer $height
 * @property string $blood_pressure
 * @property string $cholesterol_level
 * @property integer $average_hours_sleep
 * @property string $reason_using_matrix
 */
class Profiles extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profiles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['users_id', 'birthday'], 'required'],
            [['users_id', 'weight', 'height', 'average_hours_sleep'], 'integer'],
            [['birthday'], 'safe'],
            [['gender', 'occupation', 'sleeping_position', 'reason_using_matrix'], 'string'],
            [['firstname', 'lastname'], 'string', 'max' => 256],
            [['city', 'blood_pressure', 'cholesterol_level'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'users_id' => 'Users ID',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'birthday' => 'Birthday',
            'gender' => 'Gender',
            'city' => 'City',
            'occupation' => 'Occupation',
            'sleeping_position' => 'Sleeping Position',
            'weight' => 'Weight',
            'height' => 'Height',
            'blood_pressure' => 'Blood Pressure',
            'cholesterol_level' => 'Cholesterol Level',
            'average_hours_sleep' => 'Average Hours Sleep',
            'reason_using_matrix' => 'Reason Using Matrix',
        ];
    }
}
