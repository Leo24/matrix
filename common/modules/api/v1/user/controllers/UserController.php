<?php
namespace common\modules\api\v1\user\controllers;

use Yii;
use yii\rest\ActiveController;

/**
 * Site controller
 */
class UserController extends ActiveController
{

    /**
     * @inheritdoc
     */
    public $modelClass = 'app\models\User';


    /**
     * Displays test json.
     *
     * @return mixed
     */
//    public function actionIndex()
//    {
////        return $this->render('html');
//        return "{\"id\":\"UserController \"}";
//
//    }

}