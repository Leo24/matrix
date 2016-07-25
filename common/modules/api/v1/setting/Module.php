<?php

namespace common\modules\api\v1\setting;

/**
 * Class Module
 * @package common\modules\api\v1\setting
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'common\modules\api\v1\setting\controllers';

    /**
     * @var string Major API version. Property should be filled
     */
    public $apiVersion = '1';
}
