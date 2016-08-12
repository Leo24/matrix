<?php

namespace common\modules\api\v1\user\controllers\backend\actions\avatar;

use Yii;
use yii\rest\Action;
use yii\web\UploadedFile;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use common\modules\api\v1\user\models\User;
use common\modules\api\v1\user\models\Profile;

/**
 * Class UploadAction
 * Custom upload user avatar action for ProfileController
 *
 * @author Dmitriy Sobolevskiy <dmitriy.sobolevskiy@gmail.com>
 * @package common\modules\api\v1\user\controllers\backend\actions\user
 */
class UploadAction extends Action
{
    public function run()
    {
        if (Yii::$app->request->isPost) {

            $userId = User::getPayload((new User())->getAuthKey(), $payload_id = 'jti');

            /** @var $profileModel Profile */
            $profileModel = Profile::findOne(['user_id' => $userId]);

            if (!$profileModel) {
                throw new NotFoundHttpException('Use not found');
            }

            $uploadedFile = UploadedFile::getInstanceByName('avatar');

            return $profileModel->uploadAvatar($uploadedFile);
        }
        throw new BadRequestHttpException('Bad request');
    }
}
