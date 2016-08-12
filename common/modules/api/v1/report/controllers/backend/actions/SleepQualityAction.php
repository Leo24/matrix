<?php

namespace common\modules\api\v1\report\controllers\backend\actions;

use Yii;
use yii\base\Exception;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;
use common\modules\api\v1\emfit\models\SleepQuality;
use \yii\rest\Action;

/**
 * Class SleepQuality
 * @package common\modules\api\v1\report\controllers\backend\actions
 */
class SleepQualityAction extends Action
{
    /**
     * @inheritdoc
     */
    public $modelClass = SleepQuality::class;

    /**
     * Action for getting SleepQuality average data from last night and data for graph *
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
            /** @var  $sleepQualityModel SleepQuality.php */
            $sleepQualityModel = new $this->modelClass;

            $currentAverage = $sleepQualityModel->currentAverage($params);
            $sleepQualityData = $sleepQualityModel->sleepQualityGraphData($params);

            if ($sleepQualityData) {
                foreach ($sleepQualityData as $ln) {
                    $graphData[] = [
                        'chart' => [
                            'axis_x'=> $ln['from'],
                            'axis_y'=> $ln['sleep_score'],
                        ],
                    ];
                }
                $graphData[] = ['current_average' => $currentAverage];
            }

            return $graphData;

        } catch (Exception $e) {
            throw new ServerErrorHttpException('Failed to getting information for unknown reason.');
        }
    }
}
