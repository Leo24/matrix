<?php

namespace common\modules\api\v1\profile\controllers\backend;

use Yii;
use yii\base\Controller;
use yii\web\UploadedFile;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\filters\auth\HttpBearerAuth;
use common\modules\api\v1\profile\models\Profile;
use common\modules\api\v1\user\models\User;

/**
 * Class Avatar controller
 *
 * @author Dmitriy Sobolevskiy <d.sabaleuski@andersenlab.com>
 * @package common\modules\api\v1\profile\controllers\backend
 */
class AvatarController extends Controller
{
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
     * Upload user avatar action
     *
     * @return null|static
     * @throws BadRequestHttpException
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function actionUpload()
    {
        if (Yii::$app->request->isPost) {

            $userId = User::getPayload((new User())->getAuthKey(), $payload_id = 'jti');
            $profileModel = Profile::findOne(['user_id' => $userId]);

            if (!$profileModel) {
                throw new NotFoundHttpException('Use not found');
            }
            // todo нотации
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
