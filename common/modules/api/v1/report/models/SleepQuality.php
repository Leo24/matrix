<?php

namespace common\modules\api\v1\report\models;

use Yii;
use common\modules\api\v1\user\models\User;
use yii\data\ActiveDataProvider;
use \yii\db\Query;

/**
 * This is the model class for table "sleep_quality".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $timestamp
 * @property integer $sleep_class_awake_percent
 * @property integer $sleep_class_deep_percent
 * @property integer $sleep_class_light_percent
 * @property integer $sleep_class_rem_percent
 * @property integer $sleep_class_awake_duration
 * @property integer $sleep_class_deep_duration
 * @property integer $sleep_class_light_duration
 * @property integer $sleep_class_rem_duration
 */
class SleepQuality extends \yii\db\ActiveRecord
{
    public $startDate;
    public $endDate;
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
            [[
                'user_id',
                'timestamp',
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
            ], 'integer'],
            [['avg_hr', 'avg_rr', 'avg_act'], 'number'],
            [['from', 'to'], 'string'],
            [['startDate', 'endDate'], 'safe'],
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
            'from' => 'From',
            'to' => 'To',
            'timestamp' => 'Timestamp',
            'sleep_score' => 'Sleep Score',
            'duration' => 'Duration',
            'duration_in_bed' => 'Duration in Bed',
            'duration_awake' => 'Duration Awake',
            'duration_in_sleep' => 'Duration in Sleep',
            'duration_in_rem' => 'Duration in REM',
            'duration_in_light' => 'Duration in Light',
            'duration_in_deep' => 'Duration in Deep',
            'duration_sleep_onset' => 'Duration Sleep onset',
            'bedexit_duration' => 'Bedexit Duration',
            'bedexit_count' => 'Bedexit Count',
            'tossnturn_count' => 'Tossnturn Count',
            'fm_count' => 'FM Count',
            'awakenings' => 'Awakenings',
            'avg_hr' => 'Avg_hr',
            'avg_rr' => 'Avg_rr',
            'avg_act'=> 'Avg_act',
            'min_hr'=> 'Min_hr',
            'max_hr'=> 'Max_hr',
            'min_rr'=> 'Min_rr',
            'max_rr'=> 'Max_rr',

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

        return ($shortTermAverage + $longTermAverage)/2;
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

        $query = (new Query())->from('sleep_quality')
            ->select(['{{timestamp}}', '{{sleep_score}}'])
            ->where(['user_id' => $this->user_id]);

        if ($this->startDate && $this->endDate) {
            $query->andWhere(['between', 'from', $this->startDate, $this->endDate]);
        }
        return $query->all();
    }
}
