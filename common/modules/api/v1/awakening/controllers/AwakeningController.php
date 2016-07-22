<?php
namespace common\modules\api\v1\awakenings\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

/**
 * Awakenings controller
 */
class AwakeningsController extends Controller
{

    /**
     * Displays test json.
     *
     * @return mixed
     */
    public function actionIndex()
    {
//        return $this->render('html');
        return "{\"id\":\"AwakeningsController\"}";
    }

}