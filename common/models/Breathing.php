<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "breathing".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $date
 * @property string $timestamp
 * @property integer $breathing_rate
 */
class Breathing extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'breathing';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['breathing_rate'], 'number'],
            [['date', 'timestamp'], 'safe'],
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
            'timestamp' => 'Timestamp',
            'breathing_rate' => 'Breathing Rate',
        ];
    }
}
