<?php

namespace common\modules\api\v1\emfit\models;

use common\modules\api\v1\report\helper\ReportHelper;
use common\modules\api\v1\user\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use \yii\db\ActiveRecord;
use yii\db\Query;

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
    /** @var  $startDate */
    public $startDate;

    /** @var  $endDate */
    public $endDate;

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
            [['startDate', 'endDate'], 'safe'],
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
     * @return string
     */
    public function formName()
    {
        return '';
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
                'activity'         => isset($m[3]) ? $m[3] : null,
                'created_at'       => time(),
                'updated_at'       => time()
            ];
        }

        $attr = $this->attributes();
        unset($attr[0]);

        Yii::$app->db->createCommand()
            ->batchInsert(CalcData::tableName(), $attr, $rows)
            ->execute();
    }

    /**
     * Getting Heart Rate data for graph
     *
     * @param array $params
     * @param array $lastNightHeartRateParams
     *
     * @return array
     */
    public function heartRateGraphData($params, $lastNightHeartRateParams)
    {
        $heartRateData = [];

        $this->load($params);

        $query = (new Query())
            ->select(['timestamp', 'heart_rate'])
            ->from('calc_data')
            ->where(['user_id' => $this->user_id])
            ->andWhere(['not', ['heart_rate' => null]])
            ->andWhere(['between', 'timestamp', $this->startDate, $this->endDate]);

        $result = $query->all();

        if (!empty($result)) {

            foreach ($result as $heartRate) {
                if ($heartRate['heart_rate'] > 120) {
                    $heartRate['heart_rate'] = ($highest + 10) / 10;
                }
                $heartRateData[] = [
                    'timestamp'        => $heartRate['timestamp'],
                    'heart_rate' => $heartRate['heart_rate']
                ];
            }
        }

        return $heartRateData;
    }

    /**
     * Getting Breathing data for graph
     *
     * @param array $params
     * @param array $lastNightBreathingParams
     * @return array
     */
    public function breathingGraphData($params, $lastNightBreathingParams)
    {
        $breathingData = [];

        $this->load($params);

        $query = (new Query())
            ->select(['timestamp', 'respiration_rate'])
            ->from('calc_data')
            ->where(['user_id' => $this->user_id])
            ->andWhere(['not', ['respiration_rate' => null]])
            ->andWhere(['between', 'timestamp', $this->startDate, $this->endDate]);

        $result = $query->all();

        if (!empty($result)) {
            $highest = isset($lastNightBreathingParams['highest']) ?
                $lastNightBreathingParams['highest'] : ReportHelper::getMaxValue($result, 'respiration_rate');
            foreach ($result as $breathing) {
                if ($breathing['respiration_rate'] > 30) {
                    // todo уточнить что значит "http://joxi.ru/12M17Bntxxg72J".
                    // todo Параметр message будет рассчитываться на фронтенде
                    $breathing['respiration_rate'] = ($highest + 10) / 10;
                }
                $breathingData[] = [
                    'timestamp'        => $breathing['timestamp'],
                    'respiration_rate' => $breathing['respiration_rate']
                ];
            }
        }


        return $breathingData;
    }
}
