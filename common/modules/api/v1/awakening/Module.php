<?php

namespace common\modules\api\v1\awakening;

/**
 * Class Module
 * @package common\modules\api\v1\awakening
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'common\modules\api\v1\awakening\controllers';

    /**
     * @var string Major API version. Property should be filled
     */
    public $apiVersion = '1';
}
