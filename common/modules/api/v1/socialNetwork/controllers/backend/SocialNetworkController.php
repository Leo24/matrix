<?php

namespace common\modules\api\v1\socialnetwork\controllers\backend;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use common\modules\api\v1\socialnetwork\models\SocialNetwork;

/**
 * Class SocialNetwork controller
 *
 * @author Dmitriy Sobolevskiy <d.sabaleuski@andersenlab.com>
 * @package common\modules\api\v1\socialnetwork\controllers\backend
 */
//todo camelCase
class SocialnetworkController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = SocialNetwork::class;

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
