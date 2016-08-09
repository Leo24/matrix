<?php

namespace common\modules\api\v1\sleepingposition\controllers\backend;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use common\modules\api\v1\sleepingposition\models\SleepingPosition;

/**
 * Class SleepingPosition controller
 *
 * @author Dmitriy Sobolevskiy <d.sabaleuski@andersenlab.com>
 * @package common\modules\api\v1\sleepingposition\controllers\backend
 */
//todo camelCase
class SleepingpositionController extends ActiveController
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
