<?php
namespace common\modules\api\v1\notification\controllers\backend;

use common\models\Notification;
use common\modules\api\v1\notification\controllers\backend\actions\IndexAction;
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
                'create' => [''],
                'update' => ['post'],
                'delete' => ['post','delete'],
            ]
        ];

        return $behaviors;
    }


    /**
     * @inheritdoc
     */
    public $allowedMethods = [
        'POST'    => ['needAuth' => 1],
        'PUT'     => ['needAuth' => 1],
        'PATCH'   => ['needAuth' => 1],
        'DELETE'  => ['needAuth' => 1],
        'GET'     => ['needAuth' => 1],
        'HEAD'    => ['needAuth' => 1],
        'OPTIONS' => ['needAuth' => 1]
    ];

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();

        $actions['index']['class'] = IndexAction::class;

        return $actions;
    }

    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'update' => ['POST'],
            'delete' => ['DELETE'],
            'view'   => ['GET'],
        ];
    }


}