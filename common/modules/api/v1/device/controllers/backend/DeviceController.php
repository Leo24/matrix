<?php
namespace common\modules\api\v1\device\controllers\backend;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use common\models\Device;

/**
 * Device controller
 */
class DeviceController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = Device::class;

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
