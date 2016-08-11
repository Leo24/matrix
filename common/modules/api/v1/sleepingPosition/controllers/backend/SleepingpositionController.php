<?php

namespace common\modules\api\v1\sleepingPosition\controllers\backend;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use common\modules\api\v1\sleepingPosition\models\SleepingPosition;

/**
 * Class SleepingPosition controller
 *
 * @author Dmitriy Sobolevskiy <d.sabaleuski@andersenlab.com>
 * @package common\modules\api\v1\sleepingPosition\controllers\backend
 */
class SleepingPositionController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = SleepingPosition::class;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['bearerAuth'] = [
            'class' => HttpBearerAuth::className(),
        ];

        return $behaviors;
    }
}
