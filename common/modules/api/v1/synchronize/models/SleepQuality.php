<?php

namespace common\modules\api\v1\synchronize\models;

use common\modules\api\v1\user\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use \yii\db\ActiveRecord;
use yii\db\Query;

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
 *
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
            [['avg_hr', 'avg_rr', 'avg_act'], 'number'],
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
            'max_rr'               => 'Max_rr'
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
     * @param array $params
     *
     * @return array
     */
    public function sleepQualityData($params)
    {
        $this->load($params);

        $query = (new Query())
            ->select(['{{from}}', '{{sleep_score}}'])
            ->from('sleep_quality')
            ->where(['user_id' => $this->user_id]);

        if ($this->startDate && $this->endDate) {
            $query->andWhere(['between', 'from', $this->startDate, $this->endDate]);
        }
        return $query->all();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return array
     */
    public function lastNightHeartRateParams($params)
    {
        $this->load($params);
        $query = (new Query())
            ->select(['user_id', 'from as date','avg_hr as last_night', 'max_hr as highest', 'min_hr as lowest' ])
            ->from('sleep_quality')
            ->where(['user_id' => $this->user_id]);
        if ($this->currentDate) {
            $query->andWhere(['between', 'from', strtotime("-1 day", $this->currentDate), $this->currentDate]);
        }
        return $query->all();
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
        $this->user_id = $userId;
        $this->from = $data['from'];
        $this->to = $data['to'];
        $this->sleep_score = $data['sleep_score'];
        $this->duration = $data['duration'];
        $this->duration_in_bed = $data['duration_in_bed'];
        $this->duration_awake = $data['duration_awake'];
        $this->duration_in_sleep = $data['duration_in_sleep'];
        $this->duration_in_rem = $data['duration_in_rem'];
        $this->duration_in_light = $data['duration_in_light'];
        $this->duration_in_deep = $data['duration_in_deep'];
        $this->duration_sleep_onset = $data['duration_sleep_onset'];
        $this->bedexit_duration = $data['bedexit_duration'];
        $this->bedexit_count = $data['bedexit_count'];
        $this->tossnturn_count = $data['tossnturn_count'];
        $this->fm_count = $data['fm_count'];
        $this->awakenings = $data['awakenings'];
        $this->avg_hr = $data['avg_hr'];
        $this->avg_rr = $data['avg_rr'];
        $this->avg_act = $data['avg_act'];
        $this->min_hr = $data['min_hr'];
        $this->max_hr = $data['max_hr'];
        $this->min_rr = $data['min_rr'];
        $this->max_rr = $data['max_rr'];

        if (!$this->save()) {
            throw new \Exception(implode(', ', $this->getFirstErrors()));
        }
    }
}
