<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "alarms".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $date
 * @property string $frecuency
 * @property string $range
 * @property string $sound
 * @property string $label
 * @property integer $snooze
 * @property string $snooze_option
 */
class Alarms extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alarms';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'snooze'], 'integer'],
            [['date'], 'safe'],
            [['range', 'sound'], 'string'],
            [['frecuency', 'label', 'snooze_option'], 'string', 'max' => 128],
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
            'frecuency' => 'Frecuency',
            'range' => 'Range',
            'sound' => 'Sound',
            'label' => 'Label',
            'snooze' => 'Snooze',
            'snooze_option' => 'Snooze Option',
        ];
    }
}
