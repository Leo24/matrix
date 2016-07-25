<?php
namespace common\modules\api\v1\notification\controllers;

use Yii;
use yii\rest\ActiveController;

/**
 * Notification controller
 */
class NotificationController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = 'app\models\Notifications';


    /**
     * Displays test json.
     *
     * @return mixed
     */
//    public function actionIndex()
//    {
//        return $this->render('html');
//        return "{\"id\":\"NotificationController \"}";

//    }




//    /**
//     * @return array
//     */
//    public function actions()
//    {
//        $actions = parent::actions();
//
//        $actions['index']['class'] = IndexAction::class;
//
//        return $actions;
//    }
//
//    /**
//     * @inheritdoc
//     */
//    public $allowedMethods = [
//        'GET'     => ['needAuth' => 0],
//        'HEAD'    => ['needAuth' => 0],
//        'OPTIONS' => ['needAuth' => 0]
//    ];
//
//    /**
//     * @inheritdoc
//     */
//    protected function verbs()
//    {
//        return [
//            'index' => ['GET'],
//            'view'  => ['GET']
//        ];
//    }


}