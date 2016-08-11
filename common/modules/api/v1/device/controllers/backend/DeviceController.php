<?php

namespace common\modules\api\v1\device\controllers\backend;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use common\modules\api\v1\device\models\Device;

/**
 * Class DeviceController
 *
 * @author Dmitriy Sobolevskiy <d.sabaleuski@andersenlab.com>
 * @package common\modules\api\v1\device\controllers\backend
 */
class DeviceController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = Device::class;

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
        /** @var $searchModel Device */
        $searchModel = new $this->modelClass;
        return $searchModel->search(\Yii::$app->request->queryParams);
    }
}
