<?php

namespace common\modules\api\v1\notification\controllers\backend\actions;

use common\models\Notification;
use Yii;
use yii\web\HttpException;

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
    public $modelClass = Notification::class;

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
        return $model::find()->where(['user_id' => $id])->all();
    }
}