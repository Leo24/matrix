<?php

namespace common\modules\api\v1\emfit\models;

use common\modules\api\v1\user\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use \yii\db\ActiveRecord;
use yii\db\Query;

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
    /** @var  $startDate */
    public $startDate;

    /** @var  $endDate */
    public $endDate;

    /** @var  $endDate */
    public $currentDate;
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
            [['startDate', 'endDate', 'currentDate'], 'safe'],
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
     * @return string
     */
    public function formName()
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value'              => function () {
                    return time();
                },
            ],
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
        $currentTimestamp = $jsonHrvRmssdData[0][0];
        $rows = [];
        foreach ($jsonHrvRmssdData as $k => $m) {
            /** Increase time interval - 10 minutes */
            if ($m[0] > $currentTimestamp + 600) {
                $currentTimestamp = $m[0];
                $rows[$k] = [
                    'user_id'        => $userId,
                    'timestamp'      => isset($m[0]) ? $m[0] : null,
                    'rmssd'          => isset($m[1]) ? $m[1] : null,
                    'low_frequency'  => isset($m[2]) ? $m[2] : null,
                    'high_frequency' => isset($m[3]) ? $m[3] : null,
                    'created_at'     => time(),
                    'updated_at'     => time()
                ];
            }
        }

        $attr = $this->attributes();
        unset($attr[0]);

        Yii::$app->db->createCommand()
            ->batchInsert(HrvRmssdData::tableName(), $attr, $rows)
            ->execute();
    }

    /**
     *
     *
     * @param array $params
     *
     * @return array
     */
    public function heartHealthGraphData($params)
    {
        $heartHealthData = [];

        $this->load($params);

        $query = (new Query())
            ->select(['timestamp', 'rmssd as heart_rate'])
            ->from('hrv_rmssd_data')
            ->where(['user_id' => $this->user_id])
            ->andWhere(['between', 'timestamp', $this->startDate, $this->endDate]);

        $result = $query->all();

        if (!empty($result)) {
            $highest = $this->getMaxHeartHealthForLastNight($result);
            foreach ($result as $heartHealth) {
                if ($heartHealth['heart_rate'] > 120) {
                    $heartHealth['heart_rate'] = round($highest + 10);
                }
                $heartHealthData[] = [
                    'timestamp'  => $heartHealth['timestamp'],
                    'heart_rate' => $heartHealth['heart_rate']
                ];
            }
        }

        return $heartHealthData;
    }

    /**
     * @param $data
     * @return int
     */
    private function getMaxHeartHealthForLastNight($data)
    {
        $heartHealthMax = 0;
        foreach ($data as $heartHealth) {
            if ($heartHealth['heart_rate'] > $heartHealthMax) {
                $heartHealthMax = $heartHealth['heart_rate'];
            }
        }
        return $heartHealthMax;
    }

    /**
     *
     *
     * @param array $params
     *
     * @return array
     */
    public function stressGraphData($params)
    {
        $this->load($params);

        $query = (new Query())
            ->select(['timestamp', 'low_frequency as LF', 'high_frequency as HF'])
            ->from('hrv_rmssd_data')
            ->where(['user_id' => $this->user_id])
            ->andWhere(['between', 'timestamp', $this->startDate, $this->endDate]);

        return $query->all();
    }

    /**
     *
     * @param array $params
     *
     * @return array
     */
    public function lastNightAverageStressLevel($params)
    {
        $this->load($params);

        $query = (new Query())
            ->from('hrv_rmssd_data')
            ->where(['user_id' => $this->user_id])
            ->andWhere(['between', 'timestamp', strtotime("-1 day", $this->currentDate), $this->currentDate]);

        $average = $query->average('rmssd');

        return $average;
    }
}
