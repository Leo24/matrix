<?php
namespace common\modules\api\v1\user\controllers;

use common\models\User;
use Yii;
use yii\rest\ActiveController;
use yii\web\HttpException;

class UserController extends ActiveController
{
    const REGISTRATION_SUCCESS_CODE = 20;

    public $modelClass = 'app\models\User';

    public function actionRegister()
    {
        $userModel = new User;
        $userModel->scenario = 'register';
        $userModel->attributes = Yii::$app->request->post();

        $profileModel = new Profile;
        $profileModel->scenario = 'register';
        $userModel->attributes = Yii::$app->request->post();

        try {
            if($userModel->save() && $profileModel->save()) {
                throw new HttpException(200, 'Successful registration', self::REGISTRATION_SUCCESS_CODE);
            }
        }
        catch (\Exception $e) {
            var_dump($e);
        }
    }


}