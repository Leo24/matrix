<?php

namespace common\modules\api\v1\user\controllers\frontend;

use Yii;
use yii\base\Controller;
use common\models\User;

/**
 * Class UserController
 * @package common\modules\api\v1\user\controllers\frontend
 */
class UserController extends Controller
{
    /**
     * Action register a new user
     *
     * @return array
     * @throws \yii\web\HttpException
     */
    public function actionRegister()
    {
        return User::registerUser(Yii::$app->request->post());
    }
}