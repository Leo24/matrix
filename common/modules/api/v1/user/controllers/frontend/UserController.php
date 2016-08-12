<?php

namespace common\modules\api\v1\user\controllers\frontend;

use Yii;
use yii\base\Controller;
use common\modules\api\v1\user\models\User;

/**
 * Class UserController
 *
 * @author Dmitriy Sobolevskiy <d.sabaleuski@andersenlab.com>
 * @package common\modules\api\v1\user\controllers\frontend
 */
class UserController extends Controller
{
    /** @var  $modelClass User.php */
    public $modelClass = User::class;

    /**
     * Action register a new user
     *
     * @return array
     * @throws \yii\web\HttpException
     */
    public function actionRegister()
    {
        /** @var  $user User.php */
        $user = new $this->modelClass;

        return $user->registerUser(Yii::$app->request->post());
    }
}
