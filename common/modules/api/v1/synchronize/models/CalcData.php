<?php

namespace common\modules\api\v1\synchronize\models;

use common\modules\api\v1\user\models\User;
use Yii;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "calc_data".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $timestamp
 * @property double $heart_rate
 * @property double $respiration_rate
 * @property integer $activity
 *
 */
class CalcData extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%calc_data}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'activity'], 'integer'],
            [['timestamp'], 'integer'],
            [['heart_rate', 'respiration_rate'], 'number'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'               => 'ID',
            'user_id'          => 'User ID',
            'timestamp'        => 'Timestamp',
            'heart_rate'       => 'Heart Rate',
            'respiration_rate' => 'Respiration Rate',
            'activity'         => 'Activity',
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
     * Method of saving calc data
     *
     * @param $jsonCalcData
     * @param $userId
     * @throws \Exception
     */
    public function saveCalcData($jsonCalcData, $userId)
    {
//        $currentTimestamp = $jsonCalcData[0][0];
        $rows = [];
        foreach ($jsonCalcData as $k => $m) {
            // todo интервал должен быть 10 минут
            $rows[$k] = [
                'user_id'          => $userId,
                'timestamp'        => isset($m[0]) ? $m[0] : null,
                'heart_rate'       => isset($m[1]) ? $m[1] : null,
                'respiration_rate' => isset($m[2]) ? $m[2] : null,
                'activity'         => isset($m[3]) ? $m[3] : null
            ];
        }

        $attr = $this->attributes();
        unset($attr[0]);

        Yii::$app->db->createCommand()
            ->batchInsert(CalcData::tableName(), $attr, $rows)
            ->execute();
    }

    private function countCurrentStep($timestamp, $currentTimestamp)
    {
        return (int)$timestamp - (int)$currentTimestamp;
    }
}
