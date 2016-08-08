<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table 'social_network'.
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $social_network_type
 * @property string $data
 *
 * @author Dmitriy Sobolevskiy <d.sabaleuski@andersenlab.com>
 * @package common\models
 */
class SocialNetwork extends ActiveRecord
{
    const SCENARIO_REGISTER = 'register';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%social_network}}';
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_REGISTER => [
                'social_network_type',
                'user_id',
                'id',
                'data',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'social_network_type', 'data'], 'required'],
            [['user_id'], 'integer'],
            ['social_network_type', 'in', 'range' => ['facebook', 'instagram', 'pinterest', 'twitter']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'social_network_type' => Yii::t('app', 'Social Network Type'),
            'data' => Yii::t('app', 'Data'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        $fields = parent::fields();
        $fields['data'] = function ($model) {
            return $model->getSocialNetworkData();
        };
        if ($this->scenario == self::SCENARIO_REGISTER) {
            unset($fields['user_id']);
        }

        return $fields;
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
     * @return mixed
     */
    public function getSocialNetworkData()
    {
        return json_decode($this->data);
    }

    /**
     * @param $user_id
     * @param $type
     * @return bool
     */
    public static function existSocialNetwork($user_id, $type)
    {
        return (bool)SocialNetwork::findOne(['user_id' => $user_id, 'social_network_type' => $type]);
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->data = json_encode($this->data);
            return true;
        }

        return false;
    }
}
