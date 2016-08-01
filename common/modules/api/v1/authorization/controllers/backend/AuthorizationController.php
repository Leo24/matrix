<?php
namespace common\modules\api\v1\authorization\controllers\backend;

use common\models\User;

use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class AuthorizationController
 * @package common\modules\api\v1\authorization\controllers\backend
 */
class AuthorizationController extends ActiveController
{

    const SESSION_CLOSED_CODE = 15;

    /**
     * @inheritdoc
     */
    public $modelClass = 'app\models\User';

    /**
     * Token
     * @var null
     */
    protected $authorizationToken = null;

    /**
     * AuthorizationController constructor.
     * @param string $id
     * @param \yii\base\Module $module
     * @param array $config
     */
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config = []);
        $this->authorizationToken = (new User())->getAuthKey();
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['bearerAuth'] = [
            'class' => HttpBearerAuth::className(),
        ];

        return $behaviors;
    }

    /**
     * Refresh authorization token
     * @return array|null
     * @throws ForbiddenHttpException
     * @throws \yii\web\UnauthorizedHttpException
     */
    public function actionRefresh()
    {
        $user = User::findIdentityByAccessToken($this->authorizationToken);
        User::addBlackListToken($this->authorizationToken);

        return [
            'token' => $user->getJWT(),
            'exp' => User::getPayload($this->authorizationToken, $payload_id = 'exp'),
        ];
    }

    /**
     * Logout user
     * @throws HttpException
     * @throws ServerErrorHttpException
     * @throws \yii\web\UnauthorizedHttpException
     */
    public function actionLogout()
    {
        User::findIdentityByAccessToken($this->authorizationToken);

        if (User::addBlackListToken($this->authorizationToken)) {
            throw new HttpException(200, 'The session is successfully closed', self::SESSION_CLOSED_CODE);
        }
        throw new ServerErrorHttpException;
    }

}