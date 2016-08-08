<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "stress".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $date
 * @property string $timestamp
 * @property integer $high_frequency
 * @property integer $low_frequency
 */
class Stress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stress';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'high_frequency', 'low_frequency'], 'integer'],
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
            'high_frequency' => 'High Frequency',
            'low_frequency' => 'Low Frequency',
        ];
    }
}
