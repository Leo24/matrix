<?php

namespace common\modules\api\v1\sleepquality\controllers\backend\actions;

use Yii;
use yii\web\HttpException;
use common\models\SleepQuality;

/**
 * Class CreateAction
 * Custom create action for NotificationController
 *
 * @package common\modules\api\v1\notification\controllers\backend\actions
 */

class ViewAction extends \yii\rest\ViewAction
{

    /**
     * @inheritdoc
     */
    public $modelClass = SleepQuality::class;

    /**
     * Displays a model.
     *
     * @param string $id the primary key of the model.
     *
     * @return \yii\db\ActiveRecordInterface the model being displayed
     * @throws HttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function run($id)
    {
        $model = $this->modelClass;
        $startDate = Yii::$app->getRequest()->getQueryParam('{startDate}');
        $endDate = Yii::$app->getRequest()->getQueryParam('{endDate}');
        $where = ['user_id' => $id];

        if (empty($startDate)) {
            $startDate = 0;
        }

        if (empty($endDate)) {
            $endDate = 0;
        }

        $data = $model::find()
            ->where($where)
            ->andWhere(['>=', 'date', $startDate])
            ->andWhere(['<=', 'date', $endDate])
            ->all();

        return $data;
    }
}
