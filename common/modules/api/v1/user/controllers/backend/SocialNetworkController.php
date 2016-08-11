<?php

namespace common\modules\api\v1\user\controllers\backend;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use common\modules\api\v1\user\models\SocialNetwork;

/**
 * Class SocialNetwork controller
 *
 * @author Dmitriy Sobolevskiy <d.sabaleuski@andersenlab.com>
 * @package namespace common\modules\api\v1\user\controllers\backend
 */
class SocialNetworkController extends ActiveController
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
