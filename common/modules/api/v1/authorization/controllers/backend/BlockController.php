<?php

namespace common\modules\api\v1\authorization\controllers\backend;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use common\modules\api\v1\authorization\models\Block;
use common\modules\api\v1\authorization\controllers\backend\actions\block\IndexAction;

/**
 * Class BlockController
 *
 * @author Dmitriy Sobolevskiy <d.sabaleuski@andersenlab.com>
 * @package common\modules\api\v1\authorization\controllers\backend
 */
class BlockController extends ActiveController
{
    /**
     * @inheritdoc
     */
    public $modelClass = Block::class;

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
        $actions['index'] = [
                'class' => IndexAction::class,
                'modelClass' => Block::class,
        ];

        return $actions;
    }

    /**
     * @return \yii\data\ActiveDataProvider
     */
    public function indexDataProvider()
    {
        /** @var $searchModel Block */
        $searchModel = new $this->modelClass;
        return $searchModel->search(\Yii::$app->request->queryParams);
    }
}