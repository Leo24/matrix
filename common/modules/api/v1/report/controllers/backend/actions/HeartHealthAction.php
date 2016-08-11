<?php

namespace common\modules\api\v1\report\controllers\backend\actions;

use Yii;
use yii\web\HttpException;
use common\modules\api\v1\report\models\CalcData;
use common\modules\api\v1\report\models\HrvRmssdData ;

/**
 * Class HeartRateAction
 * Custom HeartRate action for ReportController
 *
 * @package common\modules\api\v1\report\controllers\backend\actions
 */

class HeartHealthAction extends \yii\rest\Action
{

    /**
     * Displays a model.
     * @return \yii\db\ActiveRecordInterface the model being displayed
     * @throws HttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function run()
    {
        /** @var  $HrvRmssdDataModel HrvRmssdData */

        $graphData = [];
        $params = \Yii::$app->request->queryParams;
        $HrvRmssdDataModel = new HrvRmssdData();
//        $SleepQualityModel = new SleepQuality();
        $HeartHealthGraphData = $HrvRmssdDataModel ->heartHealthGraphData($params);


//        $lastNightHeartRateParams = $SleepQualityModel->lastNightHeartRateParams($params);
//
//
//
//        foreach ($heartRateGraphData as $ln) {
//            $graphData[] = [
//
//                'chart' => [
//                    'axis_x'=> $ln['timestamp'],
//                    'axis_y'=> $ln['heart_rate'],
//                ],
//            ];
//        }
//        $graphData[] = $lastNightHeartRateParams;
        return $HeartHealthGraphData;
    }
}
