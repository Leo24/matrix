<?php

namespace common\modules\api\v1\report\controllers\backend\actions;

use common\modules\api\v1\synchronize\models\HrvData;
use Yii;
use yii\base\Exception;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use common\modules\api\v1\synchronize\models\CalcData;
use common\modules\api\v1\synchronize\models\HrvRmssdData;
use \yii\rest\Action;
use yii\web\ServerErrorHttpException;

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

        if (!isset($params['user_id']) || !isset($params['startDate']) || !isset($params['endDate'])) {
            throw new BadRequestHttpException('Params startDate, endDate and user_id are required.');
        }

        try {
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
        } catch (Exception $e) {
            throw new ServerErrorHttpException('Failed to getting information for unknown reason.');
        }
    }
}
