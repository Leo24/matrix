<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "awakenings".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $date
 * @property string $timestamp
 * @property integer $duration
 */
class Awakenings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'awakenings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'duration'], 'integer'],
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
            'duration' => 'Duration',
        ];
    }
}
