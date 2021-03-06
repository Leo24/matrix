<?php

namespace common\modules\api\v1\user\controllers\backend;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use common\modules\api\v1\user\models\Health;

/**
 * Class HealthController
 *
 * @author Dmitriy Sobolevskiy <d.sabaleuski@andersenlab.com>
 * @package common\modules\api\v1\user\controllers\backend
 */
class HealthController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = Health::class;

    /**
     * @inheritdoc
     */
    public $serializer = [
        'class'              => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

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

    /**
     * @inheritdoc
     */
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
        /** @var $searchModel Health */
        $searchModel = new $this->modelClass;
        return $searchModel->search(\Yii::$app->request->queryParams);
    }
}
