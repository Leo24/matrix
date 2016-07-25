<?php

namespace common\modules\api\v1\notification;

/**
 * Class Module
 * @package common\modules\api\v1\notification
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'common\modules\api\v1\notification\controllers';

    /**
     * @var string Major API version. Property should be filled
     */
    public $apiVersion = '1';
}
