<?php

namespace common\modules\api\v1\synchronize;

/**
 * Class Module
 * @package common\modules\api\v1\emfitData
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'common\modules\api\v1\synchronize\controllers';

    /**
     * @var string Major API version. Property should be filled
     */
    public $apiVersion = '1';
}
