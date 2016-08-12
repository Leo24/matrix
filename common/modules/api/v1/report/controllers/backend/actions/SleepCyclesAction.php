<?php

namespace common\modules\api\v1\report\controllers\backend\actions;

use common\modules\api\v1\synchronize\models\HrvData;
use Yii;
use yii\base\Exception;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;
use common\modules\api\v1\synchronize\models\SleepData;
use common\modules\api\v1\synchronize\models\SleepQuality;
use \yii\rest\Action;

/**
 * Class SleepCycles
 * @package common\modules\api\v1\report\controllers\backend\actions
 */
class SleepCyclesAction extends Action
{
    /**
     * Action for getting SleepCycles average data from last night and data for graph
     * @return array with graph data
     * @throws \yii\web\ServerErrorHttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function run()
    {
        $graphData = [];
        $params = \Yii::$app->request->queryParams;
        if (!isset($params['user_id']) || !isset($params['startDate']) || !isset($params['endDate']) || !isset($params['currentDate'])) {
            throw new BadRequestHttpException('Params startDate, endDate and user_id are required.');
        }
        try {
            /** @var  $SleepDataModel SleepData.php */
            $sleepDataModel = new SleepData();
            $sleepQualityModel = new SleepQuality();
            $hrvDataModel = new HrvData();

            $sleepQualityData = $sleepQualityModel->sleepQualityData($params);
            $sleepRecoveryData = $hrvDataModel->sleepRecoveryData($params);
            $sleepCyclesData = $sleepQualityModel->sleepCyclesData($params);


            $currentAverage = $sleepQualityModel->currentAverage($params);

            $sleepCyclesGraphData = $sleepDataModel->sleepCyclesGraphData($params);

            if ($sleepCyclesGraphData) {
                foreach ($sleepQualityData as $ln) {
                    $graphData[] = [
                        'chart' => [
                            'axis_x'=> $ln['timestamp'],
                            'axis_y'=> $ln['sleep_type'],
                        ],
                    ];
                }
                $graphData[] = ['sleep_quality' => $sleepQualityData];
                $graphData[] = ['sleep_recovery' => $sleepRecoveryData];
                $graphData[] = ['sleep_cycles' => $sleepCyclesData];
            }

            return $graphData;

        } catch (Exception $e) {
            throw new ServerErrorHttpException('Failed to getting information for unknown reason.');
        }
    }
}
