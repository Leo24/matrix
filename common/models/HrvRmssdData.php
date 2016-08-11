<?php

namespace common\models;

use common\modules\api\v1\user\models\User;
use Yii;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "hrv_rmssd_data".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $timestamp
 * @property integer $rmssd
 * @property integer $low_frequency
 * @property integer $high_frequency
 *
 * @property User $user
 */
class HrvRmssdData extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hrv_rmssd_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'rmssd', 'low_frequency', 'high_frequency', 'timestamp'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'             => 'ID',
            'user_id'        => 'User ID',
            'timestamp'      => 'Timestamp',
            'rmssd'          => 'Rmssd',
            'low_frequency'  => 'Low Frequency',
            'high_frequency' => 'High Frequency',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Method of saving hrv rmssd data
     *
     * @param $jsonHrvRmssdData
     * @param $userId
     * @throws \Exception
     */
    public function saveRmssdData($jsonHrvRmssdData, $userId)
    {
        $rows = [];
        foreach ($jsonHrvRmssdData as $k => $m) {
            // todo интервал должен быть 10 минут
            $rows[$k] = [
                'user_id'        => $userId,
                'timestamp'      => isset($m[0]) ? $m[0] : null,
                'rmssd'          => isset($m[1]) ? $m[1] : null,
                'low_frequency'  => isset($m[2]) ? $m[2] : null,
                'high_frequency' => isset($m[3]) ? $m[3] : null
            ];
        }

        $attr = $this->attributes();
        unset($attr[0]);

        Yii::$app->db->createCommand()
            ->batchInsert(HrvRmssdData::tableName(), $attr, $rows)
            ->execute();
    }
}
