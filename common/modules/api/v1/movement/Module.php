<?php

namespace common\modules\api\v1\movement;

/**
 * Class Module
 * @package common\modules\api\v1\movement
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'common\modules\api\v1\movement\controllers';

    /**
     * @var string Major API version. Property should be filled
     */
    public $apiVersion = '1';
}
