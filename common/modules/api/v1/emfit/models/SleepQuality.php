<?php

namespace common\modules\api\v1\emfit\models;

use common\modules\api\v1\user\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use \yii\db\ActiveRecord;
use yii\db\Query;
use yii\web\BadRequestHttpException;

/**
 * This is the model class for table "sleep_quality".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $from
 * @property integer $to
 * @property integer $sleep_score
 * @property integer $duration
 * @property integer $duration_in_bed
 * @property integer $duration_awake
 * @property integer $duration_in_sleep
 * @property integer $duration_in_rem
 * @property integer $duration_in_light
 * @property integer $duration_in_deep
 * @property integer $duration_sleep_onset
 * @property integer $bedexit_duration
 * @property integer $bedexit_count
 * @property integer $tossturn_count
 * @property integer $fm_count
 * @property integer $awakenings
 * @property float $avg_hr
 * @property float $avg_rr
 * @property float $avg_act
 * @property integer $min_hr
 * @property integer $max_hr
 * @property integer $min_rr
 * @property integer $max_rr
 * @property float $hrv_score
 * @property float $hrv_lf
 * @property float $hrv_hf
 * @property float $hrv_rmssd_evening
 * @property float $hrv_rmssd_morning
 */
class SleepQuality extends ActiveRecord
{
    /** @var  $startDate */
    public $startDate;

    /** @var  $endDate */
    public $endDate;

    /** @var  $currentDate */
    public $currentDate;

    /**
     * Primary key name
     *
     * @inheritdoc
     */
    public $primaryKey = 'id';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sleep_quality}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [
                [
                    'user_id',
                    'sleep_score',
                    'duration',
                    'duration_in_bed',
                    'duration_awake',
                    'duration_in_sleep',
                    'duration_in_rem',
                    'duration_in_light',
                    'duration_in_deep',
                    'duration_sleep_onset',
                    'bedexit_duration',
                    'bedexit_count',
                    'tossnturn_count',
                    'fm_count',
                    'awakenings',
                    'min_hr',
                    'max_hr',
                    'min_rr',
                    'max_rr',
                    'from',
                    'to'
                ],
                'integer'
            ],
            [['avg_hr', 'avg_rr', 'avg_act', 'hrv_score', 'hrv_lf', 'hrv_hf', 'hrv_rmssd_evening', 'hrv_rmssd_morning'], 'number'],
            [['startDate', 'endDate', 'currentDate'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [

            'id'                   => 'ID',
            'user_id'              => 'User ID',
            'from'                 => 'From',
            'to'                   => 'To',
            'timestamp'            => 'Timestamp',
            'sleep_score'          => 'Sleep Score',
            'duration'             => 'Duration',
            'duration_in_bed'      => 'Duration in Bed',
            'duration_awake'       => 'Duration Awake',
            'duration_in_sleep'    => 'Duration in Sleep',
            'duration_in_rem'      => 'Duration in REM',
            'duration_in_light'    => 'Duration in Light',
            'duration_in_deep'     => 'Duration in Deep',
            'duration_sleep_onset' => 'Duration Sleep onset',
            'bedexit_duration'     => 'Bedexit Duration',
            'bedexit_count'        => 'Bedexit Count',
            'tossnturn_count'      => 'Tossnturn Count',
            'fm_count'             => 'FM Count',
            'awakenings'           => 'Awakenings',
            'avg_hr'               => 'Avg_hr',
            'avg_rr'               => 'Avg_rr',
            'avg_act'              => 'Avg_act',
            'min_hr'               => 'Min_hr',
            'max_hr'               => 'Max_hr',
            'min_rr'               => 'Min_rr',
            'max_rr'               => 'Max_rr',
            'hrv_score'            => 'Hrv Score',
            'hrv_lf'               => 'Hrv Lf',
            'hrv_hf'               => 'Hrv hf',
            'hrv_rmssd_evening'    => 'Hrv rmssd evening',
            'hrv_rmssd_morning'    => 'Hrv rmssd morning'
        ];
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
     * @return string
     */
    public function formName()
    {
        return '';
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return float
     */
    public function currentAverage($params)
    {
        $this->load($params);
        $today = time();
        
        $shortTerm = (new Query())->from('sleep_quality')
            ->where(['user_id' => $this->user_id])
            ->andWhere(['between', 'from', strtotime("-1 month", $today), $today]);
        $longTerm = (new Query())->from('sleep_quality')
            ->where(['user_id' => $this->user_id])
            ->andWhere(['between', 'from', strtotime("-3 month", $today), $today]);

        $shortTermAverage = $shortTerm->average('[[sleep_score]]');
        $longTermAverage = $longTerm->average('[[sleep_score]]');

        return ($shortTermAverage + $longTermAverage) / 2;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param $params
     * @return array
     * @throws BadRequestHttpException
     */
    public function sleepQualityGraphData($params)
    {
        $this->load($params);

        $query = (new Query())
            
            ->select(['from', 'sleep_score'])
            ->from('sleep_quality')
            ->where(['user_id' => $this->user_id])
            ->andWhere(['between', 'from', $this->startDate, $this->endDate]);

        return $query->all();
    }

    /**
     *  Creates data provider instance with search query applied
     *
     * @param $params
     * @return array
     * @throws BadRequestHttpException
     */
    public function lastNightHeartRateParams($params)
    {
        $this->load($params);

        $query = (new Query())
            
            ->select(['user_id', 'from as date','avg_hr as last_night', 'max_hr as highest', 'min_hr as lowest' ])
            ->from('sleep_quality')
            ->where(['user_id' => $this->user_id])
            ->andWhere(['between', 'from', strtotime("-1 day", $this->currentDate), $this->currentDate]);

        return $query->one();
    }

    /**
     * Getting Breathing average data from last night
     *
     * @param array $params
     *
     * @return array
     */
    public function lastNightBreathingParams($params)
    {
        $this->load($params);

        $query = (new Query())
            ->select(['user_id', 'from as date','avg_rr as last_night', 'max_rr as highest', 'min_rr as lowest' ])
            ->from('sleep_quality')
            ->where(['user_id' => $this->user_id])
            ->andWhere(['between', 'from', strtotime("-1 day", $this->currentDate), $this->currentDate]);

        return $query->one();
    }

    /**
     *
     *
     * @param array $params
     *
     * @return array
     */
    public function averages($params)
    {
        $this->load($params);
        
        $query = (new Query())
            ->select(['user_id', 'hrv_score as heart_rate', 'duration as duration', 'avg_rr as breathing', 'fm_count as movement' ])
            ->from('sleep_quality')
            ->where(['user_id' => $this->user_id])
            ->andWhere(['between', 'from', strtotime("-1 day", $this->currentDate), $this->currentDate]);

        /**todo уточнить какой параметр получать последний или предпоследний*/

        return $query->one();
    }

    /**
     *
     *
     * @param array $params
     *
     * @return array
     */
    public function sleepQualityData($params)
    {
        $this->load($params);

        $query = (new Query())
            ->select(['sleep_score as sleep_quality', 'duration as duration', 'from as fall_asleep', 'tossnturn_count as tosses_turns', 'awakenings' ])
            ->from('sleep_quality')
            ->where(['user_id' => $this->user_id])
            ->andWhere(['between', 'from', strtotime("-1 day", $this->currentDate), $this->currentDate]);

        return $query->one();
    }

 

    /**
     *
     *
     * @param array $params
     *
     * @return array
     */
    public function sleepCyclesData($params)
    {
        $this->load($params);

        $query = (new Query())
            ->select(['duration_in_bed as time_asleep', 'from as fall_asleep',
                'duration_in_light as light_sleep', 'duration_in_deep as deep_sleep', 'duration_in_rem as rem_sleep', 'awakenings'])
            ->from('sleep_quality')
            ->where(['user_id' => $this->user_id])
            ->andWhere(['between', 'from', strtotime("-1 day", $this->currentDate), $this->currentDate]);

        return $query->one();
    }

    /**
     * Method of saving sleep quality
     *
     * @param $data
     * @param $userId
     * @throws \Exception
     */
    public function saveSleepQualityData($data, $userId)
    {
        $this->attributes = $data;
        $this->user_id = $userId;

        if (!$this->save()) {
            throw new \Exception(implode(', ', $this->getFirstErrors()));
        }
    }
}
