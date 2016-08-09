<?php

namespace common\modules\api\v1\report\controllers\backend\actions;

use Yii;
use yii\web\HttpException;
use common\models\HrvData;
use common\models\SleepQuality;
use common\models\SleepData;
use common\models\HrvRmssdData;
use common\models\CalcData;
use common\models\HeartFlex;

/**
 * Class CreateAction
 * Custom create action for ReportController
 *
 * @package common\modules\api\v1\report\controllers\backend\actions
 */

class ViewAction extends \yii\rest\ViewAction
{

    /**
     * @inheritdoc
     */
    public $modelClass = HrvData::class;

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
        $select = [];
        $where = ['user_id' => $id];
        $andWhere = [];

        if (!empty($startDate) && !empty($endDate)) {
            $andWhere = ['between', 'timestamp', $startDate, $endDate];
        }

        if (strpos(Yii::$app->request->url, 'report/sleep/cycles') !== false) {
            $model = SleepData::class;
            $select = ['{{user_id}}', '{{timestamp}}', '{{sleep_type}}'];
        }

        if (strpos(Yii::$app->request->url, 'report/sleep/quality') !== false) {
            $model = SleepQuality::class;
        }
        
        if (strpos(Yii::$app->request->url, 'report/stress') !== false) {
            $model = HrvRmssdData::class;
            $select = ['{{user_id}}', '{{timestamp}}', '{{low_frequency}}', '{{high_frequency}}'];
        }

        if (strpos(Yii::$app->request->url, 'report/breathing') !== false) {
            $model = CalcData::class;
            $select = ['{{user_id}}', '{{timestamp}}', '{{respiration_rate}}'];
        }

        if (strpos(Yii::$app->request->url, 'report/movement') !== false) {
            $model = '';
        }

        if (strpos(Yii::$app->request->url, 'report/daily') !== false) {
            $model = '';
        }

        $data = $model::find()
            ->select($select)
            ->where($where)
            ->andWhere($andWhere)
            ->all();

        return $data;
    }
}