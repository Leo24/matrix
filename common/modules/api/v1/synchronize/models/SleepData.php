<?php

namespace common\modules\api\v1\synchronize\models;

use common\modules\api\v1\user\models\User;
use Yii;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "sleep_data".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $timestamp
 * @property double $sleep_type
 *
 * @property User $user
 */
class SleepData extends ActiveRecord
{
    const SLEEP_TYPE_DEEP  = 'deep';
    const SLEEP_TYPE_LIGHT = 'light';
    const SLEEP_TYPE_REM   = 'rem';
    const SLEEP_TYPE_AWAKE = 'awake';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sleep_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'timestamp'], 'integer'],
            [['sleep_type'], 'number'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'user_id'    => 'User ID',
            'timestamp'  => 'Timestamp',
            'sleep_type' => 'Sleep Type',
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
     * Method of saving sleep data
     *
     * @param $jsonSleepData
     * @param $userId
     * @throws \Exception
     */
    public function saveSleepData($jsonSleepData, $userId)
    {
        $rows = [];
        foreach ($jsonSleepData as $k => $m) {
            $rows[$k] = [
                'user_id'          => $userId,
                'timestamp'        => isset($m[0]) ? $m[0] : null,
                'sleep_type'       => isset($m[1]) ? $m[1] : null
            ];
        }

        $attr = $this->attributes();
        unset($attr[0]);

        Yii::$app->db->createCommand()
            ->batchInsert(SleepData::tableName(), $attr, $rows)
            ->execute();
    }
}
