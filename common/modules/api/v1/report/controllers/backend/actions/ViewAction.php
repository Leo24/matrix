<?php

namespace common\modules\api\v1\report\controllers\backend\actions;

use Yii;
use yii\web\HttpException;
use common\models\HrvData;
use common\models\SleepQuality;
use common\models\SleepData;
use common\models\HrvRmssdData;
use common\models\CalcData;
use common\models\HeartFlex;

/**
 * Class CreateAction
 * Custom create action for ReportController
 *
 * @package common\modules\api\v1\report\controllers\backend\actions
 */

class ViewAction extends \yii\rest\ViewAction
{

    /**
     * @inheritdoc
     */
    public $modelClass = HrvData::class;

    /**
     * Displays a model.
     *
     * @param string $id the primary key of the model.
     *
     * @return \yii\db\ActiveRecordInterface the model being displayed
     * @throws HttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function run($id)
    {

        $model = $this->modelClass;
        $startDate = Yii::$app->getRequest()->getQueryParam('startDate');
        $endDate = Yii::$app->getRequest()->getQueryParam('endDate');
        $select = [];
        $where = ['user_id' => $id];
        $andWhere = [];

        if (!empty($startDate) && !empty($endDate)) {
            $andWhere = ['between', 'timestamp', $startDate, $endDate];
        }

        if (strpos(Yii::$app->request->url, '/sleep/cycles') !== false) {
            $model = SleepData::class;
            $select = ['{{user_id}}', '{{timestamp}}', '{{sleep_type}}'];
        }

        if (strpos(Yii::$app->request->url, '/sleep/quality') !== false) {
            $model = SleepQuality::class;
            if (!empty($andWhere)) {
                return $this->countSleepQuality($model, $where, $andWhere);
            } else {
                return 'startDate and endDate should be provided';
            }
        }

        if (strpos(Yii::$app->request->url, '/stress') !== false) {
            $model = HrvRmssdData::class;
            $select = ['{{user_id}}', '{{timestamp}}', '{{low_frequency}}', '{{high_frequency}}'];
        }

        if (strpos(Yii::$app->request->url, '/breathing') !== false) {
            $model = CalcData::class;
            $select = ['{{user_id}}', '{{timestamp}}', '{{respiration_rate}}'];
        }

        if (strpos(Yii::$app->request->url, 'matrix/averages') !== false) {
            $model =  SleepQuality::class;
//            $select = ['{{user_id}}', '{{timestamp}}', '{{avg_hr}}', '{{avg_rr}}', '{{duration}}', '{{tossnturn_count}}', '{{avg_act}}'];
            $select = ['user_id, timestamp, avg_hr, avg_rr as breathing, duration, tossnturn_count, avg_act'];
        }

        if (strpos(Yii::$app->request->url, '/movement') !== false) {
            $model = '';
        }

        if (strpos(Yii::$app->request->url, '/daily') !== false) {
            $model = '';
        }

        $data = $model::find()
            ->select($select)
            ->where($where)
            ->andWhere($andWhere)
            ->all();

        return $data;
    }


    protected function countSleepQuality($model, $where, $andWhere)
    {

        $graphData = [];

        $today = date('Y-m-d H:i:s');
        $minusMonth = date('Y-m-dTH:i:sZ', strtotime($today . "-1 month"));
        $minusTreeMonth = date('Y-m-dTH:i:sZ', strtotime($today . "-1 month"));
        $shortTermAverage = (new \yii\db\Query())->from('sleep_quality')
            ->where($where)
            ->andWhere(['between', 'from', $minusMonth, $today]);
        $longTermAverage = (new \yii\db\Query())->from('sleep_quality')
            ->where($where)
            ->andWhere(['between', 'from', $minusTreeMonth, $today]);

        $shortTermAverage = $shortTermAverage->average('[[sleep_score]]');
        $longTermAverage = $longTermAverage->average('[[sleep_score]]');

        $currentAverage = ($shortTermAverage + $longTermAverage)/2;

        $sleepQualityData = $model::find()
            ->select(['{{timestamp}}', '{{sleep_score}}'])
            ->where($where)
            ->andWhere($andWhere)
            ->all();

        foreach ($sleepQualityData as $ln) {
            $graphData[] = [
                'chart' => [
                    'axis_x'=> $ln->timestamp,
                    'axis_y'=> $ln->sleep_score,
                ],
                'current_average' => $currentAverage
            ];
        }

        return $graphData;
    }
}
