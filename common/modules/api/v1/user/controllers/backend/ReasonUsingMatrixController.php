<?php

namespace common\modules\api\v1\user\controllers\backend;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use common\modules\api\v1\user\models\ReasonUsingMatrix;

/**
 * Class ReasonUsingMatrix controller
 *
 * @author Dmitriy Sobolevskiy <d.sabaleuski@andersenlab.com>
 * @package common\modules\api\v1\user\controllers\backend
 */
class ReasonUsingMatrixController extends ActiveController
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
