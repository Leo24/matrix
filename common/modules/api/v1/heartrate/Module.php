<?php

namespace common\modules\api\v1\heartrate;

/**
 * Class Module
 * @package common\modules\api\v1\heartrate
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'common\modules\api\v1\heartrate\controllers';

    /**
     * @var string Major API version. Property should be filled
     */
    public $apiVersion = '1';
}