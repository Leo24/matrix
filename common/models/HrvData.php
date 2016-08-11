<?php

namespace common\models;

use common\modules\api\v1\user\models\User;
use Yii;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "hrv_data".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $timestamp
 * @property double $start_rmssd
 * @property double $end_rmssd
 * @property double $total_recovery
 * @property double $recovery_ratio
 * @property double $recovery_rate
 *
 * @property User $user
 */
class HrvData extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hrv_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['start_rmssd', 'end_rmssd', 'total_recovery', 'recovery_ratio', 'recovery_rate'], 'number'],
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
            'start_rmssd'    => 'Start Rmssd',
            'end_rmssd'      => 'End Rmssd',
            'total_recovery' => 'Total Recovery',
            'recovery_ratio' => 'Recovery Ratio',
            'recovery_rate'  => 'Recovery Rate',
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
     * Method of saving hrv data
     *
     * @param $jsonHrvData
     * @param $userId
     * @throws \Exception
     */
    public function saveHrvData($jsonHrvData, $userId)
    {
        $rows = [];

//        var_dump($jsonHrvData);
//        var_dump($jsonHrvData[0][4]);
//        var_dump((float)$jsonHrvData[0][4]);
//        exit;

        foreach ($jsonHrvData as $k => $m) {

            $str = '-9.3';
//
//            echo gettype('-5.3') . PHP_EOL;
//            echo floatval((string)'-5.3') . PHP_EOL;
//
//            echo $m[2] . PHP_EOL;
//            echo gettype($m[2]) . PHP_EOL;

            /**
             * проблема в том, что первый символ не минус :)
             */
            die(var_dump(
                [
                    substr($str, 0, 1), // получаем первый символ строки образца
                    substr($m[2], 0, 1), // получаем первый символ строки исходного массива

                    ord(substr($str, 0, 1)), // ASCII код минуса
                    ord(substr($m[2], 0, 1)), // ASCII код чего-то похожего визуально на минус

                    (float) $m[2],
                    $m[2],

                    (float) $str
                ]
            ));


            $rows[$k] = [
                'user_id'        => $userId,
                'start_rmssd'    => isset($m[0]) ? $m[0] : null,
                'end_rmssd'      => isset($m[1]) ? $m[1] : null,
                'total_recovery' => isset($m[2]) ? $m[2] : null,
                'recovery_ratio' => isset($m[3]) ? $m[3] : null,
                'recovery_rate'  => isset($m[4]) ? $m[4] : null
            ];
        }

        $attr = $this->attributes();
        unset($attr[0]);

        Yii::$app->db->createCommand()
            ->batchInsert(HrvData::tableName(), $attr, $rows)->execute();

    }
}
