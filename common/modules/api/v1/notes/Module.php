<?php

namespace common\modules\api\v1\notes;

/**
 * Class Module
 * @package common\modules\api\v1\notes
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'common\modules\api\v1\notes\controllers';

    /**
     * @var string Major API version. Property should be filled
     */
    public $apiVersion = '1';
}
