<?php
namespace common\modules\api\v1\user\controllers\backend;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\web\HttpException;
use common\models\User;
use common\modules\api\v1\user\controllers\backend\actions\user\DeleteAction;

/**
 * Class UserController
 * @package common\modules\api\v1\user\controllers
 */
class UserController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = User::class;

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
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();

        // customize actions for templates
        $actions['delete']['class'] = DeleteAction::class;

        return $actions;
    }

    /**
     * Update user password action
     *
     * @throws HttpException
     * @throws \yii\web\UnauthorizedHttpException
     */
    public function actionPassword()
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