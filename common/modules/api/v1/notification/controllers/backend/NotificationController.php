<?php
namespace common\modules\api\v1\notification\controllers\backend;

use common\models\Notification;
use common\modules\api\v1\notification\controllers\backend\actions\ViewAction;
use common\modules\api\v1\notification\controllers\backend\actions\MarkviewedAction;
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
        $behaviors['verbs'] = [
            'class' => \yii\filters\VerbFilter::className(),
            'actions' => [
                'index'  => ['get'],
                'view'   => ['get'],
                'create' => ['post'],
                'update' => ['patch'],
                'delete' => ['post','delete'],
            ]
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

        // disable actions
        unset($actions['create']);

        return $actions;
    }




}