<?php

namespace common\modules\api\v1\profile\controllers\backend;

use Yii;
use yii\base\Controller;
use yii\filters\auth\HttpBearerAuth;
use common\modules\api\v1\profile\models\Profile;
use common\modules\api\v1\profile\controllers\backend\actions\avatar\UploadAction;

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
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();

        $additional = [
            'upload' => [
                'class'      => UploadAction::class,
                'modelClass' => Profile::class,
            ]
        ];

        return $additional + $actions;
    }
}
