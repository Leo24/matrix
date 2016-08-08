<?php
namespace common\modules\api\v1\notification\controllers\backend;

use common\models\Notification;
use common\modules\api\v1\notification\controllers\backend\actions\ViewAction;
use common\modules\api\v1\notification\controllers\backend\actions\CreateAction;
use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;

/**
 * Notification controller
 */
class NotificationController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = Notification::class;

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


    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();

        $actions['create']['class'] = CreateAction::class;
        $actions['view']['class'] = ViewAction::class;

        // disable actions
        unset($actions['index']);

        return $actions;
    }
}
