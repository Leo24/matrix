<?php
namespace common\modules\api\v1\settings\controllers\backend;

use common\modules\api\v1\settings\models\SettingNotification;
use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;

/**
 * Class NotificationController
 * @package common\modules\api\v1\settings\controllers\backend
 */
class NotificationController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = SettingNotification::class;

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
        /** @var  $searchModel SettingNotification */
        $searchModel = new $this->modelClass;
        return $searchModel->search(\Yii::$app->request->queryParams);
    }

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
}
