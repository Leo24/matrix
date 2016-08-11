<?php

namespace common\modules\api\v1\user\controllers\backend;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use common\modules\api\v1\user\models\Profile;

/**
 * Class Profile controller
 *
 * @author Dmitriy Sobolevskiy <d.sabaleuski@andersenlab.com>
 * @package common\modules\api\v1\user\controllers\backend
 */
class ProfileController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = Profile::class;

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

        // disable actions
        unset($actions['delete'], $actions['create']);

        return $actions;
    }
}
