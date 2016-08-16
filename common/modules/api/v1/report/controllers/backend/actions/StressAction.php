<?php

namespace common\modules\api\v1\report\controllers\backend\actions;

use common\modules\api\v1\emfit\models\HrvData;
use Yii;
use yii\base\Exception;
use yii\web\BadRequestHttpException;
use \yii\rest\Action;
use yii\web\ServerErrorHttpException;
use common\modules\api\v1\emfit\models\HrvRmssdData;
use common\modules\api\v1\report\helper\ReportHelper;


/**
 * Class StressAction
 *
 * @package common\modules\api\v1\report\controllers\backend\actions
 */
class StressAction extends Action
{
    /**
     * Action for getting Stress average data from last night and data for graph
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
            /** @var  $HrvDataModel HrvData.php */
            $hrvDataModel = new HrvData();

            /** @var  $hrvRmssdDataModel HrvRmssdData.php */
            $hrvRmssdDataModel = new HrvRmssdData();

            $stressGraphData = $hrvRmssdDataModel->stressGraphData($params);

            $lastNightStressParams = $hrvDataModel->lastNightStressParams($params);
            $lastNightStressParams['last_night'] = $hrvRmssdDataModel->lastNightAverageStressLevel($params);

            if ($stressGraphData) {
                foreach ($stressGraphData as $ln) {
                    $graphData[] = [
                        'chart' => [
                            'axis_x'    => $ln['timestamp'],
                            'axis_y_LF' => $ln['LF'],
                            'axis_y_HF' => $ln['HF'],
                        ]
                    ];
                }
                $lastNightStressParams['message'] = $reportHelper->getStressMessage($lastNightStressParams['evening_average'],
                    $lastNightStressParams['morning_average']);
                $graphData[] = $lastNightStressParams;
            }
            return $graphData;
        } catch (Exception $e) {
            throw new ServerErrorHttpException('Failed to getting information for unknown reason.');
        }
    }
}
