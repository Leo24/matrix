<?php
namespace common\modules\api\v1\notification\controllers\backend;

use common\modules\api\v1\notification\models\Notification;
use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;

/**
 * Notification controller
 */

/**
 * Class NotificationController
 *
 * @package common\modules\api\v1\notification\controllers\backend
 */
class NotificationController extends ActiveController
{
    public $serializer = [
        'class'              => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    /**
     * @inheritdoc
     */
    public $modelClass = Notification::class;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['bearerAuth'] = [
            'class' => HttpBearerAuth::className(),
        ];
        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();

        $actions['index']['prepareDataProvider'] = [$this, 'indexDataProvider'];
        return $actions;
    }

    /**
     * @return \yii\data\ActiveDataProvider
     */
    public function indexDataProvider()
    {
        /** @var  $searchModel Notification */
        $searchModel = new $this->modelClass;
        return $searchModel->search(\Yii::$app->request->queryParams);
    }
}
