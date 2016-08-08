<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "notifications".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $date
 * @property string $title
 * @property string $body
 * @property string $type
 * @property string $icon
 */
class Notification extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['date'], 'safe'],
            [['body'], 'string'],
            [['title', 'type', 'icon'], 'string', 'max' => 255],
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
            'date' => 'Date',
            'title' => 'Title',
            'body' => 'Body',
            'type' => 'Type',
            'icon' => 'Icon',
        ];
    }
}
