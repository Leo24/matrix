<?php

namespace common\modules\api\v1\user;

/**
 * Class Module
 * @package common\modules\api\v1\user
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'common\modules\api\v1\user\controllers';

    /**
     * @var string Major API version. Property should be filled
     */
    public $apiVersion = '1';
}
