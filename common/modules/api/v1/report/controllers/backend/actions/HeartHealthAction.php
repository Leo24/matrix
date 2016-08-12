<?php

namespace common\modules\api\v1\report\controllers\backend\actions;

use common\modules\api\v1\emfit\models\HrvData;
use Yii;
use yii\base\Exception;
use yii\web\BadRequestHttpException;
use common\modules\api\v1\emfit\models\HrvRmssdData;
use \yii\rest\Action;
use yii\web\ServerErrorHttpException;

/**
 * Class HeartHealthAction
 * @package common\modules\api\v1\report\controllers\backend\actions
 */
class HeartHealthAction extends Action
{
    /**
     * Action for getting HeartHealth average data from last night and data for graph
     * @return array with graph data
     * @throws \yii\web\ServerErrorHttpException
     * @throws \yii\web\BadRequestHttpException
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
