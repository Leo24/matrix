<?php

namespace common\modules\api\v1\device\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\behaviors\TimestampBehavior;
use common\modules\api\v1\user\models\User;

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
            'id'         => Yii::t('app', 'Id'),
            'user_id'    => Yii::t('app', 'User Id'),
            'name'       => Yii::t('app', 'Name'),
            'position'   => Yii::t('app', 'Position'),
            'pin'        => Yii::t('app', 'Pin'),
            'pw'         => Yii::t('app', 'PW'),
            'sn'         => Yii::t('app', 'SN'),
            'updated_at' => Yii::t('app', 'Updated_at'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_REGISTER] = [
            'name',
            'position',
            'pin',
            'pw',
            'sn',
        ];

        return $scenarios;
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
            [['name', 'pin', 'pw', 'sn', 'position', 'user_id'], 'trim'],
            [
                ['name', 'pin', 'pw', 'sn'],
                'required',
                'on' => self::SCENARIO_REGISTER
            ],
            [['name', 'pin', 'pw', 'sn', 'position'], 'string', 'max' => 255],
            [['name', 'pin', 'pw', 'sn', 'position', 'user_id'], 'safe'],
            [['user_id'], 'integer'],
            [
                'sn',
                'unique',
                'targetClass' => self::className(),
                'message'     => Yii::t('app', 'Device exists'),
                'on'          => self::SCENARIO_REGISTER
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

    /**
     * @return string
     */
    public function formName()
    {
        return '';
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'user_id'  => $this->user_id,
            'name'     => $this->name,
            'position' => $this->position,
            'pin'      => $this->pin,
            'pw'       => $this->pw,
            'sn'       => $this->sn,
        ]);

        return $dataProvider;
    }
}
