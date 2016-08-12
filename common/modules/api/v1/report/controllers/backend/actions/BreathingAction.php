<?php

namespace common\modules\api\v1\report\controllers\backend\actions;

use common\modules\api\v1\synchronize\models\HrvData;
use Yii;
use yii\web\HttpException;
use common\modules\api\v1\synchronize\models\CalcData;
use common\modules\api\v1\synchronize\models\SleepQuality;
use \yii\rest\Action;

/**
 * Class BreathingAction
 * Custom BreathingAction action for ReportController
 *
 * @package common\modules\api\v1\report\controllers\backend\actions
 */

class BreathingAction extends Action
{
    /**
     * Displays a model.
     * @return \yii\db\ActiveRecordInterface the model being displayed
     * @throws HttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function run()
    {
        $graphData = [];
        $params = \Yii::$app->request->queryParams;
        $calcDataModel = new CalcData();
        $sleepQualityModel = new SleepQuality();

        $breathingGraphData = $calcDataModel->breathingGraphData($params);
        $lastNightBreathingParams = $sleepQualityModel->lastNightBreathingParams($params);

        if (is_array($breathingGraphData)) {
            foreach ($breathingGraphData as $ln) {
                $graphData[] = [
                    'chart' => [
                        'axis_x' => $ln['timestamp'],
                        'axis_y' => $ln['respiration_rate'],
                    ],
                ];
            }
        } else {
            $graphData[] = $breathingGraphData;
        }
        $graphData[] = $lastNightBreathingParams;

        return $graphData;
    }
}
