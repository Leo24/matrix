<?php
namespace common\modules\api\v1\synchronize\controllers;

use Yii;
use yii\rest\ActiveController;
use common\modules\api\v1\synchronize\controllers\actions\emfitData\SaveAction;
use \common\modules\api\v1\synchronize\models\SleepQuality;

/**
 * Class EmfitDataController
 *
 * @package common\modules\api\v1\synchronize\controllers
 */
class EmfitDataController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = SleepQuality::class;

    public function actions()
    {
        $actions = parent::actions();

        $additional = [
            'save-data' => [
                'class'       => SaveAction::class,
                'modelClass'  => SleepQuality::class,
                'checkAccess' => [$this, 'checkAccess']
            ]
        ];

        return $actions + $additional;
    }
}
