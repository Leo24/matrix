<?php

namespace common\modules\api\v1\socialNetwork;

/**
 * Class Module
 *
 * @author Dmitriy Sobolevskiy <d.sabaleuski@andersenlab.com>
 * @package common\modules\api\v1\socialNetwork
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'common\modules\api\v1\socialNetwork\controllers';

    /**
     * @var string Major API version. Property should be filled
     */
    public $apiVersion = '1';
}
