<?php

namespace common\modules\api\v1\health\controllers\backend;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use common\modules\api\v1\health\models\Health;

/**
 * Class HealthController
 *
 * @author Dmitriy Sobolevskiy <d.sabaleuski@andersenlab.com>
 * @package common\modules\api\v1\health\controllers\backend
 */
class HealthController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = Health::class;

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
