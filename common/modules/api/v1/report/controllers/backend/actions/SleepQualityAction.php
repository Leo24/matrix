<?php

namespace common\modules\api\v1\report\controllers\backend\actions;

use Yii;
use yii\web\HttpException;
use common\modules\api\v1\report\models\SleepQuality;

/**
 * Class SleepCycles
 * Custom SleepCycles action for ReportController
 *
 * @package common\modules\api\v1\report\controllers\backend\actions
 */

class SleepQualityAction extends \yii\rest\Action
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
        /** @var  $SleepQualityModel SleepQuality */
        $graphData = [];
        $params = \Yii::$app->request->queryParams;
        $SleepQualityModel = new $this->modelClass;
        $currentAverage = $SleepQualityModel->currentAverage($params);

        $sleepQualityData = $SleepQualityModel->sleepQualityData($params);

        foreach ($sleepQualityData as $ln) {
            $graphData[] = [
                'chart' => [
                    'axis_x'=> $ln['timestamp'],
                    'axis_y'=> $ln['sleep_score'],
                ],
            ];
        }
        $graphData[] = ['current_average' => $currentAverage];

        return $graphData;
    }
}
