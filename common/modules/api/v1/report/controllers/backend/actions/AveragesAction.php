<?php

namespace common\modules\api\v1\report\controllers\backend\actions;

use common\modules\api\v1\synchronize\models\HrvData;
use Yii;
use yii\web\HttpException;
use common\modules\api\v1\synchronize\models\CalcData;
use common\modules\api\v1\synchronize\models\HrvRmssdData;
use \yii\rest\Action;

/**
 * Class HeartRateAction
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
        return 'This is AveragesAction';
    }
}
