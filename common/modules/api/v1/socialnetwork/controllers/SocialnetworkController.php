<?php
namespace common\modules\api\v1\socialnetwork\controllers;

use common\models\SocialNetwork;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

/**
 * Socialnetworks controller
 */
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