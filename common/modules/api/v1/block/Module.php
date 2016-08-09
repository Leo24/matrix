<?php

namespace common\modules\api\v1\block;

/**
 * Class Module
 * @package common\modules\api\v1\block
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'common\modules\api\v1\block\controllers';

    /**
     * @var string Major API version. Property should be filled
     */
    public $apiVersion = '1';
}
