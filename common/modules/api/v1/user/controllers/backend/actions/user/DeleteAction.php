<?php
namespace common\modules\api\v1\user\controllers\backend\actions\user;

use Yii;
use yii\web\HttpException;
use yii\web\ServerErrorHttpException;
use yii\web\ForbiddenHttpException;
use common\models\User;

/**
 * Class DeleteAction
 * Custom delete action for UserController
 *
 * @author Dmitriy Sobolevskiy <dmitriy.sobolevskiy@gmail.com>
 * @package common\modules\api\v1\user\controllers\backend\actions\user
 */
class DeleteAction extends \yii\rest\DeleteAction
{
    /**
     * @param mixed $id
     * @throws ForbiddenHttpException
     * @throws HttpException
     * @throws ServerErrorHttpException
     * @throws \Exception
     * @throws \yii\web\UnauthorizedHttpException
     */
    public function run($id)
    {
        $authorizationToken = (new User())->getAuthKey();

        /* @var User $model */
        $model = User::findIdentityByAccessToken($authorizationToken);

        if ($model === null) {
            throw new HttpException(404, "User not found");
        }

        if ((int)$id !== $model->id) {
            throw new ForbiddenHttpException('Access denied');
        }

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        if ($model->delete() === false) {
            throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
        }

        Yii::$app->getResponse()->setStatusCode(204);
    }
}