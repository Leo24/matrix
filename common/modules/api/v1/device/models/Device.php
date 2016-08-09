<?php

namespace common\modules\api\v1\device\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use common\models\User;

/**
 * This is the model class for table 'device'
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $position
 * @property string $pin
 * @property string $pw
 * @property string $sn
 * @property integer $updated_at
 *
 * @author Dmitriy Sobolevskiy <d.sabaleuski@andersenlab.com>
 * @package common\modules\api\v1\device\models
 */
class Device extends ActiveRecord
{
    const SCENARIO_REGISTER = 'register';

    public $sleeping_position;

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
            'id' => Yii::t('app', 'Id'),
            'user_id' => Yii::t('app', 'User Id'),
            'name' => Yii::t('app', 'Name'),
            'position' => Yii::t('app', 'Position'),
            'pin' => Yii::t('app', 'Pin'),
            'pw' => Yii::t('app', 'PW'),
            'sn' => Yii::t('app', 'SN'),
            'updated_at' => Yii::t('app', 'Updated_at'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        //todo нормально назвать переменную
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
        //todo namespace add
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
