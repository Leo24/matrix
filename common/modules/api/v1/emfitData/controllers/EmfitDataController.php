<?php
namespace common\modules\api\v1\emfitData\controllers;

use common\models\SleepQuality;
use Yii;
use yii\rest\ActiveController;
use common\modules\api\v1\emfitData\controllers\actions\SaveAction;

/**
 * Class EmfitDataController
 *
 * @package common\modules\api\v1\emfitdata\controllers
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
