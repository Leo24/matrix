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

class CreateAction extends \yii\rest\CreateAction
{

    /**
     * @inheritdoc
     */
    public $modelClass = Notification::class;

    /**
     * Displays a model.
     *
     * @return \yii\db\ActiveRecordInterface the model being displayed
     * @throws HttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function run()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $data = $request->bodyParams;
            $model = $this->modelClass;
            return $model::find()->where(['>=', 'created_at', $data['date']])->andWhere(['user_id' => $data['user_id']])->all();
        }
    }
}