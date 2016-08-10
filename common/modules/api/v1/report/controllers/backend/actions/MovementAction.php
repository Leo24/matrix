<?php

namespace common\modules\api\v1\report\controllers\backend\actions;

use Yii;
use yii\web\HttpException;
use common\modules\api\v1\report\models\SleepData;

/**
 * Class Movements
 * Custom Movements action for ReportController
 *
 * @package common\modules\api\v1\report\controllers\backend\actions
 */

class MovementAction extends \yii\rest\Action
{

    /**
     * @inheritdoc
     */
    public $modelClass = SleepData::class;

    /**
     * Displays a model.
     *
     * @param string $id the primary key of the model.
     *
     * @return \yii\db\ActiveRecordInterface the model being displayed
     * @throws HttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function run()
    {
        /** @var  $SleepDataModel SleepData */

//        $SleepDataModel = new $this->modelClass;
        
//        return $SleepDataModel->search(\Yii::$app->request->queryParams);
//        $data = \Yii::$app->request->queryParams;

            return 'Movement Action';

    }
}
