<?php

namespace common\modules\api\v1\devices;

/**
 * Class Module
 * @package common\modules\api\v1\devices
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'common\modules\api\v1\devices\controllers';

    /**
     * @var string Major API version. Property should be filled
     */
    public $apiVersion = '1';
}
