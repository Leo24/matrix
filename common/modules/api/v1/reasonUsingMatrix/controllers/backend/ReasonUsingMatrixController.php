<?php

namespace common\modules\api\v1\reasonusingmatrix\controllers\backend;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use common\modules\api\v1\reasonusingmatrix\models\ReasonUsingMatrix;

/**
 * Class SleepingPosition controller
 *
 * @author Dmitriy Sobolevskiy <d.sabaleuski@andersenlab.com>
 * @package common\modules\api\v1\reasonusingmatrix\controllers\backend
 */
class ReasonusingmatrixController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = ReasonUsingMatrix::class;

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
