<?php
namespace common\modules\api\v1\report\controllers\backend;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use common\modules\api\v1\report\models\SleepData;
use common\modules\api\v1\report\models\SleepQuality;
use common\modules\api\v1\report\models\HrvData;
use common\modules\api\v1\report\controllers\backend\actions\SleepQualityAction;
use common\modules\api\v1\report\controllers\backend\actions\HeartRateAction;
use common\modules\api\v1\report\controllers\backend\actions\HeartHealthAction;

/**
 * Report controller
 */
class ReportController extends ActiveController
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
    public $modelClass = HrvData::class;

    /**
     * @return array
     */
    public function actions()
    {

        $actions = parent::actions();
        
        $additional = [
            'sleep-quality' => [
                'class'       => SleepQualityAction::class,
                'modelClass'  => SleepQuality::class,
                'checkAccess' => [$this, 'checkAccess']
            ],
            'heart-rate' => [
                'class'       => HeartRateAction::class,
                'modelClass'  => SleepData::class,
                'checkAccess' => [$this, 'checkAccess']
            ],
            'heart-health' => [
                'class'       => HeartHealthAction::class,
                'modelClass'  => SleepData::class,
                'checkAccess' => [$this, 'checkAccess']
            ]
        ];

        return $actions + $additional;
    }
}
