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

class AveragesAction extends Action
{
    /**
     * Displays a model.
     * @return \yii\db\ActiveRecordInterface the model being displayed
     * @throws HttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function run()
    {
        $params = \Yii::$app->request->queryParams;
        /** @var  $sleepQualityModel SleepQuality.php */
        $sleepQualityModel = new SleepQuality();
        return $sleepQualityModel->averages($params);
    }
}
