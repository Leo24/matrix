<?php
namespace common\modules\api\v1\sleepquality\controllers\backend;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use common\models\SleepQuality;
use common\modules\api\v1\sleepquality\controllers\backend\actions\ViewAction;

/**
 * Notification controller
 */
class SleepqualityController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = SleepQuality::class;

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

        $actions['view']['class'] = ViewAction::class;


        return $actions;
    }
}
