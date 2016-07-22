<?php

namespace common\modules\api\v1\breathing;

/**
 * Class Module
 * @package common\modules\api\v1\breathing
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'common\modules\api\v1\breathing\controllers';

    /**
     * @var string Major API version. Property should be filled
     */
    public $apiVersion = '1';
}
