<?php

namespace common\modules\api\v1\authorization\controllers\backend\actions\block;

use Yii;
use common\modules\api\v1\user\models\User;
use yii\data\ActiveDataProvider;

/**
 * Class IndexAction
 * Custom index action for BlockController
 *
 * @author Dmitriy Sobolevskiy <dmitriy.sobolevskiy@gmail.com>
 * @package common\modules\api\v1\authorization\controllers\backend\actions\block
 */
class IndexAction extends \yii\rest\IndexAction
{
    /**
     * Prepares the data provider that should return the requested collection of the models.
     *
     * @inheritdoc
     */
    protected function prepareDataProvider()
    {
        if ($this->prepareDataProvider !== null) {
            return call_user_func($this->prepareDataProvider, $this);
        }

        /* @var $modelClass \yii\db\BaseActiveRecord */
        $modelClass = $this->modelClass;

        $authorizationToken = (new User())->getAuthKey();

        /* @var User $model */
        $model = User::findIdentityByAccessToken($authorizationToken);
        $condition = ['user_id' => $model->id];

        return new ActiveDataProvider([
            'query' => $modelClass::find()->where($condition),
        ]);
    }
}
