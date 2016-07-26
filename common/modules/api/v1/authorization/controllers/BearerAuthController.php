<?php
namespace common\modules\api\v1\authorization\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;

class BearerAuthController extends ActiveController
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'bearerAuth' => [
                'class' => HttpBearerAuth::className()
            ]
        ]);
    }
}