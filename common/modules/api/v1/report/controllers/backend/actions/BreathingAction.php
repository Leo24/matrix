<?php

namespace common\modules\api\v1\report\controllers\backend\actions;

use common\modules\api\v1\synchronize\models\HrvData;
use Yii;
use yii\base\Exception;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use common\modules\api\v1\synchronize\models\CalcData;
use common\modules\api\v1\synchronize\models\SleepQuality;
use \yii\rest\Action;
use yii\web\ServerErrorHttpException;

/**
 * Class BreathingAction
 *
 *
 * @package common\modules\api\v1\report\controllers\backend\actions
 */
class BreathingAction extends Action
{
    /**
     * Action for getting Breathing average data from last night and data for graph
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
            /** @var  $calcDataModel CalcData.php */
            $calcDataModel = new CalcData();

            /** @var  $sleepQualityModel SleepQuality.php */
            $sleepQualityModel = new SleepQuality();

            $breathingGraphData = $calcDataModel->breathingGraphData($params);
            $lastNightBreathingParams = $sleepQualityModel->lastNightBreathingParams($params);

            if ($breathingGraphData) {
                foreach ($breathingGraphData as $ln) {
                    $graphData[] = [
                        'chart' => [
                            'axis_x' => $ln['timestamp'],
                            'axis_y' => $ln['respiration_rate'],
                        ],
                    ];
                }
                $graphData[] = $lastNightBreathingParams ;
            }
            return $graphData;
        } catch (Exception $e) {
            throw new ServerErrorHttpException('Failed to getting information for unknown reason.');
        }
    }
}
