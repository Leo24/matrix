<?php

namespace common\modules\api\v1\report\controllers\backend\actions;

use Yii;
use yii\web\HttpException;
use common\modules\api\v1\synchronize\models\SleepQuality;
use \yii\rest\Action;

/**
 * Class SleepCycles
 * Custom SleepCycles action for ReportController
 *
 * @package common\modules\api\v1\report\controllers\backend\actions
 */

class SleepQualityAction extends Action
{
    /**
     * @inheritdoc
     */
    public $modelClass = SleepQuality::class;

    /**
     * Displays a model.
     *
     * @return \yii\db\ActiveRecordInterface the model being displayed
     * @throws HttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function run()
    {
        $graphData = [];
        $params = \Yii::$app->request->queryParams;

        /** @var  $sleepQualityModel SleepQuality.php */
        $sleepQualityModel = new $this->modelClass;

        $currentAverage = $sleepQualityModel->currentAverage($params);

        $sleepQualityData = $sleepQualityModel->sleepQualityData($params);

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
    }
}
