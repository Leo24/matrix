<?php
namespace common\modules\api\v1\profile\controllers\backend;

use common\models\Profile;
use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;

/**
 * Profile controller
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
}