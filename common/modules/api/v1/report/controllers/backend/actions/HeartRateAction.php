<?php

namespace common\modules\api\v1\report\controllers\backend\actions;

use common\modules\api\v1\report\helper\ReportHelper;
use Yii;
use yii\base\Exception;
use yii\web\BadRequestHttpException;
use common\modules\api\v1\emfit\models\CalcData;
use common\modules\api\v1\emfit\models\SleepQuality;
use \yii\rest\Action;
use yii\web\ServerErrorHttpException;

/**
 * Class HeartRateAction
 * @package common\modules\api\v1\report\controllers\backend\actions
 */
class HeartRateAction extends Action
{
    /**
     * Action for getting HeartRate average data from last night and data for graph
     * @return array with graph data
     * @throws \yii\web\ServerErrorHttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function run()
    {
        /** @var  $reportHelper ReportHelper.php */
        $reportHelper = new ReportHelper();

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

            $lastNightHeartRateParams = $sleepQualityModel->lastNightHeartRateParams($params);

            $heartRateGraphData = $calcDataModel->heartRateGraphData($params, $lastNightHeartRateParams);

            if ($heartRateGraphData) {
                foreach ($heartRateGraphData as $ln) {
                    $graphData[] = [
                        'chart' => [
                            'axis_x' => $ln['timestamp'],
                            'axis_y' => $ln['heart_rate'],
                        ],
                    ];
                }

                $lastNightHeartRateParams['message'] = $reportHelper->getHeartRateMessage($lastNightHeartRateParams['last_night']);
                $graphData[] = $lastNightHeartRateParams;
            }
            return $graphData;
        } catch (Exception $e) {
            throw new ServerErrorHttpException('Failed to getting information for unknown reason.');
        }
    }
}
