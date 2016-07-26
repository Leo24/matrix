<?php

namespace common\modules\api\v1\authorization\controllers;

use common\models\User;
use yii\rest\Controller;
use Yii;
use yii\web\UnauthorizedHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class AuthorizationController
 * @package common\modules\api\v1\authorization\controllers
 */
class AuthorizationController extends Controller
{
    const USER_NOT_FOUND_CODE = 10;
    const FORBIDDEN_ACCESS_CODE = 11;
    const UNAUTHORIZED_BLOCK_CODE = 14;
    const SESSION_CLOSED_CODE = 15;

    /**
     * Authorization Headers
     * @var null|string
     */
    protected $authorizationHeaders = null;

    /**
     * Token
     * @var null
     */
    protected $authorizationToken = null;

    /**
     * UserController constructor.
     * @param string $id
     * @param \yii\base\Module $module
     * @param array $config
     */
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config = []);
        $this->authorizationHeaders = Yii::$app->getRequest()->getHeaders()->get('Authorization');

        if ($this->authorizationHeaders !== null && preg_match("/^Bearer\\s+(.*?)$/", $this->authorizationHeaders, $matches)) {
            if (isset($matches[1])) {
                $this->authorizationToken = $matches[1];
            }
        }
    }

    /**
     * User login
     * @return null|static
     * @throws \yii\base\InvalidConfigException
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionLogin()
    {
        $model = User::findOne(['email' => Yii::$app->getRequest()->getBodyParam('email')]);
        if (empty($model)) {
            throw new NotFoundHttpException('User not found', self::USER_NOT_FOUND_CODE);
        }
        if ($model->validatePassword(Yii::$app->getRequest()->getBodyParam('password'))) {
            $model->last_login = Yii::$app->formatter->asTimestamp(date_create());
            return ['token' => $model->getJWT()];
        } else {
            throw new ForbiddenHttpException('Access denied', self::FORBIDDEN_ACCESS_CODE);
        }
    }

    /**
     * Refresh auth token
     * @return array|null
     * @throws ForbiddenHttpException
     * @throws \yii\web\UnauthorizedHttpException
     */
    public function actionRefresh()
    {
        if ($this->authorizationToken) {
            if (User::isBlocked($this->authorizationToken)) {
                throw new UnauthorizedHttpException('Token is blocked', self::UNAUTHORIZED_BLOCK_CODE);
            }
            $user = User::findIdentityByAccessToken($this->authorizationToken);
            if (!$user) {
                throw new ForbiddenHttpException();
            } else {
                User::addBlackListToken($this->authorizationToken);
                return [
                    'token' => $user->getJWT(),
                    'exp' => User::getPayload($this->authorizationToken, $payload_id = 'exp'),
                ];
            }
        }
        throw new ForbiddenHttpException();
    }

    /**
     * Logout user
     * @throws HttpException
     * @throws ServerErrorHttpException
     * @throws \yii\web\UnauthorizedHttpException
     */
    public function actionLogout()
    {
        if ($this->authorizationToken) {
            User::findIdentityByAccessToken($this->authorizationToken);

            if (User::addBlackListToken($this->authorizationToken)) {
                throw new HttpException(200, 'The session is successfully closed', self::SESSION_CLOSED_CODE);
            }
        }
        throw new ServerErrorHttpException;
    }

}