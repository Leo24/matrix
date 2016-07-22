<?php

namespace common\modules\api\v1\alarms;

/**
 * Class Module
 * @package common\modules\api\v1\alarms
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'common\modules\api\v1\alarms\controllers';

    /**
     * @var string Major API version. Property should be filled
     */
    public $apiVersion = '1';
}
