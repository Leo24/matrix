<?php
namespace common\modules\api\v1\sleepcycle\controllers;

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
 * Sleepcycle controller
 */
class SleepcycleController extends Controller
{

    /**
     * Displays test json.
     *
     * @return mixed
     */
    public function actionIndex()
    {
//        return $this->render('html');
        return "{\"id\":\"SleepcycleController \"}";

    }

}