<?php

namespace common\modules\api\v1\report\controllers\backend\actions;

use Yii;
use yii\web\HttpException;
use common\modules\api\v1\synchronize\models\CalcData;
use common\modules\api\v1\synchronize\models\SleepQuality;
use \yii\rest\Action;

/**
 * Class HeartRateAction
 * Custom HeartRate action for ReportController
 *
 * @package common\modules\api\v1\report\controllers\backend\actions
 */
class HeartRateAction extends Action
{
    /**
     * Displays a model.
     * @return \yii\db\ActiveRecordInterface the model being displayed
     * @throws HttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function run()
    {
        /** @var  $CalcDataModel CalcData */
        /** @var  $SleepQualityModel $SleepQuality */

        $graphData = [];
        $params = \Yii::$app->request->queryParams;
        $calcDataModel = new CalcData();
        $sleepQualityModel = new SleepQuality();

        $heartRateGraphData = $calcDataModel->heartRateGraphData($params);
        $lastNightHeartRateParams = $sleepQualityModel->lastNightHeartRateParams($params);

        if ($heartRateGraphData) {
            foreach ($heartRateGraphData as $ln) {
                $graphData[] = [
                    'chart' => [
                        'axis_x' => $ln['timestamp'],
                        'axis_y' => $ln['heart_rate'],
                    ],
                ];
            }
            $graphData[] = $lastNightHeartRateParams;
        }

        return $graphData;
    }
}
