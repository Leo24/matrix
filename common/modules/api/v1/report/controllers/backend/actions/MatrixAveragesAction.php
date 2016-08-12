<?php

namespace common\modules\api\v1\report\controllers\backend\actions;

use Yii;
use yii\web\HttpException;
use common\modules\api\v1\synchronize\models\SleepQuality;
use \yii\rest\Action;

/**
 * Class AveragesAction
 * Custom Averages action for ReportController
 *
 * @package common\modules\api\v1\report\controllers\backend\actions
 */

class MatrixAveragesAction extends Action
{
    /**
     * @return array with matrix averages
     * @throws \yii\web\ServerErrorHttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function run()
    {
        $params = \Yii::$app->request->queryParams;
        /** @var  $sleepQualityModel SleepQuality.php */
        $sleepQualityModel = new SleepQuality();
        return $sleepQualityModel->averages($params);
    }
}
