<?php

namespace common\modules\api\v1\settings\models;

use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "setting_notification".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $banner
 * @property integer $general
 * @property integer $preview_text
 * @property integer $alert_sound
 * @property integer $vibrate
 * @property integer $email
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @package common\models
 */
class SettingNotification extends ActiveRecord
{

    /**
     * Table name
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%setting_notification}}';
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
    public function fields()
    {
        return [
            'id',
            'user_id',
            'general',
            'banner',
            'preview_text',
            'alert_sound',
            'vibrate',
            'email'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'general', 'banner', 'preview_text', 'alert_sound', 'vibrate', 'email'], 'integer'],
            [['user_id'], 'required'],
        ];
    }

    public function formName()
    {
        return '';
    }

    /**
     * Attributes labels
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id'      => 'User ID',
            'general'      => 'Allow Notification',
            'banner'       => 'Notification Banner',
            'preview_text' => 'Show Preview Text',
            'alert_sound'  => 'Alert Sound',
            'vibrate'      => 'Vibrate',
            'email'        => 'Email Notification'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id']);
    }

    /**
     * Create new record of setting notification for new register user
     *
     * @param $userId
     */
    public function createDefaultRecordForNewRegisterUser($userId)
    {
        $model = new SettingNotification();
        $model->user_id = $userId;
        $model->save();
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
            'query'      => $query
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['user_id' => $this->user_id]);

        return $dataProvider;
    }
}