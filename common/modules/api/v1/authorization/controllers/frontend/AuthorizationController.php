<?php

namespace common\modules\api\v1\authorization\controllers\frontend;

use Yii;
use yii\rest\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use common\modules\api\v1\user\models\User;

/**
 * Class AuthorizationController
 *
 * @author Dmitriy Sobolevskiy <d.sabaleuski@andersenlab.com>
 * @package common\modules\api\v1\authorization\controllers\frontend
 */
class AuthorizationController extends Controller
{
    /**
     * Response codes
     */
    const USER_NOT_FOUND_CODE = 10;
    const FORBIDDEN_ACCESS_CODE = 11;

    /**
     * User login action
     *
     * @return null|static
     * @throws \yii\base\InvalidConfigException
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionLogin()
    {
        /** @var $model User */
        $model = User::findOne(['email' => Yii::$app->getRequest()->getBodyParam('email')]);

        if (empty($model)) {
            throw new NotFoundHttpException('User not found', self::USER_NOT_FOUND_CODE);
        }

        $password = Yii::$app->getRequest()->getBodyParam('password');

        if (!empty($password) && $model->validatePassword($password)) {
            $model->scenario = 'login';

            $model->save();

            return ['token' => $model->getJWT()];
        } else {
            throw new ForbiddenHttpException('Access denied', self::FORBIDDEN_ACCESS_CODE);
        }
    }
}
