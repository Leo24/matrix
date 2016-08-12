<?php

namespace common\modules\api\v1\report\controllers\backend\actions;

use Yii;
use yii\web\BadRequestHttpException;
use common\modules\api\v1\emfit\models\SleepQuality;
use \yii\rest\Action;

/**
 * Class MatrixAverages
 *
 * @package common\modules\api\v1\report\controllers\backend\actions
 */

class MatrixAveragesAction extends Action
{
    /**
     * Action for getting MatrixAverages
     * @return array with matrix averages
     * @throws \yii\web\ServerErrorHttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function run()
    {
        $params = \Yii::$app->request->queryParams;

        if (!isset($params['user_id']) || !isset($params['currentDate'])) {
            throw new BadRequestHttpException('Params currentDate and user_id are required.');
        }

        /** @var  $sleepQualityModel SleepQuality.php */
        $sleepQualityModel = new SleepQuality();
        return $sleepQualityModel->averages($params);
    }
}
