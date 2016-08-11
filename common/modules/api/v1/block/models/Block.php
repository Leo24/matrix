<?php

namespace common\modules\api\v1\block\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table 'block'.
 *
 * @property string $token
 * @property integer $user_id
 * @property integer $created_at
 * @property integer $expired_at
 *
 * @author Dmitriy Sobolevskiy <d.sabaleuski@andersenlab.com>
 * @package common\modules\api\v1\block\models
 */
class Block extends ActiveRecord
{
    const SCENARIO_CREATE_BLOCK = 'create';

    /**
     * Table name
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%block}}';
    }

    /**
     * Primary key name
     *
     * @inheritdoc
     */
    public $primaryKey = 'token';

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            'token',
            'user_id',
            'created_at',
            'expired_at',
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarion = parent::scenarios();
        $scenarion[self::SCENARIO_CREATE_BLOCK] = [
            'token',
            'user_id',
            'created_at',
            'expired_at',
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
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => null,
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
            [['user_id', 'token', 'expired_at'], 'safe'],
            [['token'], 'string', 'max' => 255],
            [['expired_at'], 'integer'],
            [['user_id', 'token', 'expired_at'], 'required', 'on' => self::SCENARIO_CREATE_BLOCK],
        ];
    }

    /**
     * Attributes labels
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id'    => Yii::t('app', 'User ID'),
            'token'      => Yii::t('app', 'Token'),
            'created_at' => Yii::t('app', 'Created at'),
            'expired_at' => Yii::t('app', 'Expired at'),
        ];
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

        $query->andFilterWhere(['token' => $this->token]);

        return $dataProvider;
    }
}
