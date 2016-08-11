<?php

namespace common\modules\api\v1\profile\controllers\backend\actions\avatar;

use Yii;
use yii\rest\Action;
use yii\web\UploadedFile;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use common\modules\api\v1\user\models\User;
use common\modules\api\v1\profile\models\Profile;

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

            $profileModel->setScenario(Profile::SCENARIO_UPLOAD_AVATAR);
            $profileModel->avatar = UploadedFile::getInstanceByName('avatar');

            if ($profileModel->validate()) {
                $profileModel->deleteAvatar();
                $profileModel->avatar_url = $profileModel->getAvatarUrl($profileModel->uploadAvatar());
                $profileModel->save();
                return $profileModel;
            }

            throw new HttpException(422, 'Validation exception');
        }
        throw new BadRequestHttpException('Bad request');
    }
}
