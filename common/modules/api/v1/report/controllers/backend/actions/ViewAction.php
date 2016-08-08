<?php

namespace common\modules\api\v1\report\controllers\backend\actions;

use Yii;
use yii\web\HttpException;
use common\models\HrvData;

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
        $filters = json_decode(Yii::$app->getRequest()->getQueryParam('filters'));
        $lastDate = Yii::$app->getRequest()->getQueryParam('last_date');
        $where = ['user_id' => $id];

        if (empty($lastDate)) {
            $lastDate = 0;
        }

        if (!empty($filters->viewed)) {
            $where['viewed'] =  $filters->viewed;
        }

        if (!empty($filters->type)) {
            $type['type'] = $filters->type;
        }

        $data = $model::find()
            ->where($where)
            ->andWhere(['>=', 'created_at', $lastDate])
            ->all();

        return $data;
    }
}