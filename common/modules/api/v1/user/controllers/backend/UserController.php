<?php
namespace common\modules\api\v1\user\controllers\backend;

use common\models\User;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;

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
}