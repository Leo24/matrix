<?php

namespace common\modules\api\v1\user\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use yii\behaviors\TimestampBehavior;
use yii\db\Exception;

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
 * @package common\modules\api\v1\user\models
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
            [['height'], 'number'],
            ['blood_type', 'in', 'range' => ['A', 'B', 'AB', 'O']],
            ['user_id', 'unique', 'targetClass' => self::className(), 'message' => Yii::t('app', 'User Id exists')],
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
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Create new record of health information for new register user
     *
     * @param $userId
     * @throws Exception
     */
    public function createDefaultRecordForNewRegisterUser($userId)
    {
        $this->user_id = $userId;
        if (!$this->save()) {
            throw new Exception(implode(', ', $this->getFirstErrors()));
        }
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

        $query->andFilterWhere(['user_id' => $this->user_id]);

        return $dataProvider;
    }
}
