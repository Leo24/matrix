<?php

namespace common\modules\api\v1\user\controllers\backend;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use common\modules\api\v1\user\models\User;
use common\modules\api\v1\user\controllers\backend\actions\user\DeleteAction;
use common\modules\api\v1\user\controllers\backend\actions\user\PasswordAction;

/**
 * Class UserController
 *
 * @author Dmitriy Sobolevskiy <d.sabaleuski@andersenlab.com>
 * @package common\modules\api\v1\user\controllers\backend
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
        $actions['delete']['class'] = DeleteAction::class;

        $additional = [
            'password' => [
                'class'      => PasswordAction::class,
                'modelClass' => User::class,
            ]
        ];

        return $additional + $actions;
    }
}
