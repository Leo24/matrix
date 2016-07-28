<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "movements".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $date
 * @property string $timestamp
 * @property string $movement_type
 */
class Movements extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'movements';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['date', 'timestamp'], 'safe'],
            [['movement_type'], 'string'],
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
            'movement_type' => 'Movement Type',
        ];
    }
}
