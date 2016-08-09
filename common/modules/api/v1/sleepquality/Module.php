<?php

namespace common\modules\api\v1\sleepquality;

/**
 * Class Module
 * @package common\modules\api\v1\sleepquality
 */
class Module extends \yii\base\Module
{
    // todo camelCase
    public $controllerNamespace = 'common\modules\api\v1\sleepquality\controllers';

    /**
     * @var string Major API version. Property should be filled
     */
    public $apiVersion = '1';
}
