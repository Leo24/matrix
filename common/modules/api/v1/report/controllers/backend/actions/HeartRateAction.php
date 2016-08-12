<?php

namespace common\modules\api\v1\report\controllers\backend\actions;

use Yii;
use yii\base\Exception;
use yii\web\BadRequestHttpException;
use common\modules\api\v1\synchronize\models\CalcData;
use common\modules\api\v1\synchronize\models\SleepQuality;
use \yii\rest\Action;
use yii\web\ServerErrorHttpException;

/**
 * Class HeartRateAction
 * 
 * Action for getting HeartRate average data from last night and data for graph
 *
 * @package common\modules\api\v1\report\controllers\backend\actions
 */
class HeartRateAction extends Action
{
    /**
     * @return array with graph data
     * @throws \yii\web\ServerErrorHttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function run()
    {
        $graphData = [];
        $params = \Yii::$app->request->queryParams;
        if (!isset($params['user_id']) || !isset($params['startDate']) || !isset($params['endDate']) || !isset($params['currentDate'])) {
            throw new BadRequestHttpException('Params startDate, endDate, currentDate and user_id are required.');
        }

        try {
            /** @var  $CalcDataModel CalcData */
            $calcDataModel = new CalcData();
            /** @var  $SleepQualityModel $SleepQuality */
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
        } catch (Exception $e) {
            throw new ServerErrorHttpException('Failed to getting information for unknown reason.');
        }
    }
}
