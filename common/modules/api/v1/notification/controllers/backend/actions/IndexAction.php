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

class IndexAction extends \yii\rest\IndexAction
{
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
        
    }
}