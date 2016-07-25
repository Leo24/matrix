<?php

namespace common\modules\api\v1\alarm;

/**
 * Class Module
 * @package common\modules\api\v1\alarm
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'common\modules\api\v1\alarm\controllers';

    /**
     * @var string Major API version. Property should be filled
     */
    public $apiVersion = '1';
}
