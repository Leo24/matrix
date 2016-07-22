<?php

namespace common\modules\api\v1\notifications;

/**
 * Class Module
 * @package common\modules\api\v1\notifications
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'common\modules\api\v1\notifications\controllers';

    /**
     * @var string Major API version. Property should be filled
     */
    public $apiVersion = '1';
}
