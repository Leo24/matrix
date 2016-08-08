<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "heart_flex".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $date
 * @property string $timestamp
 * @property integer $heart_flex_value
 */
class HeartFlex extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'heart_flex';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'heart_flex_value'], 'integer'],
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
            'heart_flex_value' => 'Heart Flex Value',
        ];
    }
}
