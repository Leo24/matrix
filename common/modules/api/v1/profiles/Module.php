<?php

namespace common\modules\api\v1\profiles;

/**
 * Class Module
 * @package common\modules\api\v1\profiles
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'common\modules\api\v1\profiles\controllers';

    /**
     * @var string Major API version. Property should be filled
     */
    public $apiVersion = '1';
}
