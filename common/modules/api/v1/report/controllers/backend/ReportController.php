<?php
namespace common\modules\api\v1\report\controllers\backend;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use common\modules\api\v1\report\models\SleepData;
use common\modules\api\v1\report\models\SleepQuality;
use common\modules\api\v1\report\models\HrvData;
use common\modules\api\v1\report\controllers\backend\actions\SleepQualityAction;
use common\modules\api\v1\report\controllers\backend\actions\MovementAction;

/**
 * Report controller
 */
class ReportController extends ActiveController
{

    public $serializer = [
        'class'              => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * @inheritdoc
     */
    public $modelClass = HrvData::class;


    public function actions()
    {

        $actions = parent::actions();
        
        $additional = [
            'sleep-quality' => [
                'class'       => SleepQualityAction::class,
                'modelClass'  => SleepQuality::class,
                'checkAccess' => [$this, 'checkAccess']
            ],
            'movement' => [
                'class'       => MovementAction::class,
                'modelClass'  => SleepData::class,
                'checkAccess' => [$this, 'checkAccess']
            ]
        ];

        return $actions + $additional;
    }
}
