<?php
namespace common\modules\api\v1\emfit\controllers;

use Yii;
use yii\rest\ActiveController;
use common\modules\api\v1\emfit\controllers\actions\synchronize\SaveAction;
use \common\modules\api\v1\emfit\models\SleepQuality;

/**
 * Class SynchronizeController
 *
 * @package common\modules\api\v1\synchronize\controllers
 */
class SynchronizeController extends ActiveController
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
