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
 * Class BreathingAction
 *
 * @package common\modules\api\v1\report\controllers\backend\actions
 */
class BreathingAction extends Action
{
    /**
     * Action for getting Breathing average data from last night and data for graph
     *
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
            /** @var  $calcDataModel CalcData.php */
            $calcDataModel = new CalcData();

            /** @var  $sleepQualityModel SleepQuality.php */
            $sleepQualityModel = new SleepQuality();

            $lastNightBreathingParams = $sleepQualityModel->lastNightBreathingParams($params);

            $breathingGraphData = $calcDataModel->breathingGraphData($params, $lastNightBreathingParams);

            if ($breathingGraphData) {
                foreach ($breathingGraphData as $ln) {
                    $graphData[] = [
                        'chart' => [
                            'axis_x' => $ln['timestamp'],
                            'axis_y' => $ln['respiration_rate'],
                        ],
                    ];
                }
                $lastNightBreathingParams['message'] = $reportHelper->getBreathingMessage((int) $lastNightBreathingParams['last_night']);
                $graphData[] = $lastNightBreathingParams;
            }
            return $graphData;
        } catch (Exception $e) {
            throw new ServerErrorHttpException('Failed to getting information for unknown reason.');
        }
    }
}
