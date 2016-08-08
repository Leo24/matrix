<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
/**
 * Class Profile
 * @package common\models
 */
class Device extends ActiveRecord
{
    public $sleeping_position;
    const SCENARIO_REGISTER = 'register';
    /**
     * Primary key name
     *
     * @inheritdoc
     */
    public $primaryKey = 'id';
    /**
     * Table name
     *
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%device}}';
    }
    /**
     * Attribute labels
     *
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'        => 'Id',
            'user_id'   => 'User Id',
            'name'      => 'Name',
            'position'  => 'Position',
            'pin'       => 'Pin',
            'pw'        => 'PW',
            'sn'        => 'SN',
            'updated_at'=> 'Updated_at',
        ];
    }
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarion = parent::scenarios();
        $scenarion[self::SCENARIO_REGISTER] = [
            'name',
            'position',
            'pin',
            'pw',
            'sn',
        ];
        return $scenarion;
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
            [['name', 'pin', 'pw', 'sn'], 'trim'],
            [
                ['name', 'pin', 'pw', 'sn'],
                'required',
                'on' => self::SCENARIO_REGISTER
            ],

            [['name', 'pin', 'pw', 'sn'], 'string', 'max' => 255],
            ['sn', 'unique', 'targetClass' => self::className(), 'message' => Yii::t('app', 'Device exists')],

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}