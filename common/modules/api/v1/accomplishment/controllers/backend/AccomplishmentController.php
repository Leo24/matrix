<?php
namespace common\modules\api\v1\accomplishment\controllers\backend;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use common\modules\api\v1\accomplishment\models\Accomplishment;


/**
 * Class AccomplishmentController
 *
 * @author Dmitriy Sobolevskiy <d.sabaleuski@andersenlab.com>
 * @package common\modules\api\v1\accomplishment\controllers\backend
 */
class AccomplishmentController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = Accomplishment::class;

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