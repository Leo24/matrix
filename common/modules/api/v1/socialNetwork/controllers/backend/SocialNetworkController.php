<?php
namespace common\modules\api\v1\socialnetwork\controllers\backend;

use common\models\SocialNetwork;
use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;

/**
 * Profile controller
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