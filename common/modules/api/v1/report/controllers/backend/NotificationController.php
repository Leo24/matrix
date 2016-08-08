<?php
namespace common\modules\api\v1\report\controllers\backend;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use common\models\HrvData;
use common\modules\api\v1\report\controllers\backend\actions\ViewAction;

/**
 * Report controller
 */
class ReportController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = HrvData::class;

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
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();

        $actions['view']['class'] = ViewAction::class;


        return $actions;
    }
}
