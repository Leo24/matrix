<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "device".
 *
 * @package common\models
 */
class Device extends ActiveRecord
{
    /**
     * Table name
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%device}}';
    }

    /**
     * Primary key
     * @inheritdoc
     */
    public static function primaryKey()
    {
        return 'user_id';
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            'user_id',
            'name',
            'position',
            'pin',
            'sn',
            'pw',
            'updated_at',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'name', 'position', 'pin', 'sn', 'pw'], 'safe'],
            [['name', 'pin', 'sn', 'pw'], 'string', 'max' => 255],
            [['user_id', 'updated_at'], 'integer'],
            [['user_id', 'name', 'position', 'pin', 'sn', 'pw'], 'required'],
            ['position', 'in', 'range' => ['left', 'right', 'middle']],
        ];
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
     * Attributes labels
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('app', 'User ID'),
            'name' =>  Yii::t('app', 'Device name'),
            'pin' =>  Yii::t('app', 'Device PIN'),
            'sn' =>  Yii::t('app', 'Device SN'),
            'pw' =>  Yii::t('app', 'Device PW'),
            'updated_at' =>  Yii::t('app', 'Updated at'),
        ];
    }
}
