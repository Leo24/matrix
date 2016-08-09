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

    // todo тут скорей всего тоже нужно будет переписать
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
        $andWhere = [];

        if (!empty($startDate) && !empty($endDate)) {
            $andWhere = ['between', 'date', $startDate, $endDate];
        }

        $data = $model::find()
            ->where($where)
            ->andWhere($andWhere)
            ->all();

        return $data;
    }
}
