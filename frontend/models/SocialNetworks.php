<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "social_networks".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $social_network_type
 * @property string $data
 *
 * @property User $user
 */
class SocialNetworks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'social_networks';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'social_network_type', 'data'], 'required'],
            [['user_id'], 'integer'],
            [['social_network_type', 'data'], 'string'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'social_network_type' => 'Social Network Type',
            'data' => 'Data',
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
