<?php

namespace common\modules\api\v1\user\controllers\backend\actions\user;

use Yii;
use yii\rest\Action;
use yii\web\HttpException;
use common\modules\api\v1\user\models\User;

/**
 * Class PasswordAction
 * Custom change password action for UserController
 *
 * @author Dmitriy Sobolevskiy <dmitriy.sobolevskiy@gmail.com>
 * @package common\modules\api\v1\user\controllers\backend\actions\user
 */
class PasswordAction extends Action
{
    /**
     * @throws HttpException
     * @throws \yii\web\UnauthorizedHttpException
     */
    public function run()
    {
        $authorizationToken = (new User())->getAuthKey();
        $current = Yii::$app->request->post('current_password');

        /* @var User $model */
        $model = User::findIdentityByAccessToken($authorizationToken);

        if ($current != null && $model->validatePassword($current)) {
            $model->scenario = User::SCENARIO_UPDATE_PASSWORD;
            $model->attributes = Yii::$app->request->post();
            if ($model->save()) {
                throw new HttpException(200, 'Completed successfully');
            }
            throw new HttpException(422, 'Validation exception');
        }
        throw new HttpException(422, 'Current password invalid');
    }
}
