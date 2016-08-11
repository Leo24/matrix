<?php

namespace common\modules\api\v1\report\controllers\backend\actions;

use common\modules\api\v1\synchronize\models\HrvData;
use Yii;
use yii\web\HttpException;
use common\modules\api\v1\synchronize\models\CalcData;
use common\modules\api\v1\synchronize\models\HrvRmssdData;
use \yii\rest\Action;

/**
 * Class HeartRateAction
 * Custom HeartRate action for ReportController
 *
 * @package common\modules\api\v1\report\controllers\backend\actions
 */

class HeartHealthAction extends Action
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

        /** @var  $hrvRmssdDataModel HrvRmssdData.php */
        $hrvRmssdDataModel = new HrvRmssdData();
        /** @var  $hrvDataModel HrvData.php */
        $hrvDataModel = new HrvData();
        $heartHealthGraphData = $hrvRmssdDataModel->heartHealthGraphData($params);
        $lastNightHeartHealthParams = $hrvDataModel->lastNightHeartHealthParams($params);
        if ($heartHealthGraphData) {
            foreach ($heartHealthGraphData as $ln) {
                $graphData[] = [

                    'chart' => [
                        'axis_x'=> $ln['timestamp'],
                        'axis_y'=> $ln['heart_rate'],
                    ],
                ];
            }
            $graphData[] = $lastNightHeartHealthParams;
        }

        return $graphData;
    }
}
