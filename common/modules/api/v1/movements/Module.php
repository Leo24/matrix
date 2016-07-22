<?php

namespace common\modules\api\v1\movements;

/**
 * Class Module
 * @package common\modules\api\v1\movements
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'common\modules\api\v1\movements\controllers';

    /**
     * @var string Major API version. Property should be filled
     */
    public $apiVersion = '1';
}
