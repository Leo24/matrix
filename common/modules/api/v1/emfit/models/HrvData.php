<?php

namespace common\modules\api\v1\emfit\models;

use common\modules\api\v1\user\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use \yii\db\ActiveRecord;
use yii\db\Query;

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

    /** @var  $startDate */
    public $startDate;

    /** @var  $endDate */
    public $endDate;

    /** @var  $currentDate */
    public $currentDate;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hrv_data';
    }

    /**
     * @return string
     */
    public function formName()
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'timestamp'], 'integer'],
            [['start_rmssd', 'end_rmssd', 'total_recovery', 'recovery_ratio', 'recovery_rate'], 'number'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['startDate', 'endDate', 'currentDate'], 'safe']
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
            'timestamp' => 'Timestamp',
            'start_rmssd' => 'Start Rmssd',
            'end_rmssd' => 'End Rmssd',
            'total_recovery' => 'Total Recovery',
            'recovery_ratio' => 'Recovery Ratio',
            'recovery_rate' => 'Recovery Rate',
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
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => function () {
                    return time();
                },
            ],
        ];
    }

    /**
     * Method of saving hrv data
     *
     * @param $jsonHrvData
     * @param $userId
     * @param $timestamp
     * @throws \yii\db\Exception
     */
    public function saveHrvData($jsonHrvData, $userId, $timestamp)
    {
        $rows = [];

        foreach ($jsonHrvData as $k => $m) {
            $rows[$k] = [
                'user_id' => $userId,
                'start_rmssd' => isset($m[0]) ? (float)$m[0] : null,
                'end_rmssd' => isset($m[1]) ? (float)$m[1] : null,
                'total_recovery' => isset($m[2]) ? (float)$m[2] : null,
                'recovery_ratio' => isset($m[3]) ? (float)$m[3] : null,
                'recovery_rate' => isset($m[4]) ? (float)$m[4] : null,
                'created_at' => time(),
                'updated_at' => time(),
                'timestamp' => $timestamp
            ];
        }

        $attr = $this->attributes();
        unset($attr[0]);

        Yii::$app->db->createCommand()
            ->batchInsert(HrvData::tableName(), $attr, $rows)->execute();
    }

    /**
     *
     *
     * @param array $params
     *
     * @return array
     */
    public function lastNightHeartHealthParams($params)
    {
        $this->load($params);

        $query = (new Query())
            ->select([
                'user_id',
                '(start_rmssd + end_rmssd)/2 as last_night',
                'start_rmssd as evening_average',
                'end_rmssd as morning_average',
                'recovery_ratio as recovery'
            ])
            ->from('hrv_data')
            ->where(['user_id' => $this->user_id])
            ->andWhere(['between', 'timestamp', $this->startDate, $this->endDate]);

        return $query->one();
    }
    /**
     *
     *
     * @param array $params
     *
     * @return array
     */
    public function lastNightStressParams($params)
    {
        $this->load($params);

        $query = (new Query())
            ->select([
                'user_id',
                'start_rmssd as evening_average',
                'end_rmssd as morning_average',
                'total_recovery as recovery'
            ])
            ->from('hrv_data')
            ->where(['user_id' => $this->user_id])
            ->andWhere(['between', 'timestamp', $this->startDate, $this->endDate]);

        return $query->one();
    }

    /**
     *
     *
     * @param array $params
     *
     * @return array
     */
    public function sleepRecoveryData($params)
    {
        $this->load($params);

        $query = (new Query())
            ->select(['timestamp','total_recovery as recovery', 'start_rmssd as evening_hrv', 'end_rmssd as morning_hrv', 'recovery_rate as increase'])->distinct()
            ->from('hrv_data')
            ->where(['user_id' => $this->user_id])
            ->andWhere(['between', 'timestamp', strtotime("-1 day", $this->currentDate), $this->currentDate]);

        return $query->one();
    }
}
