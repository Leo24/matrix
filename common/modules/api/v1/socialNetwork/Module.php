<?php

namespace common\modules\api\v1\socialnetwork;

/**
 * Class Module
 *
 * @author Dmitriy Sobolevskiy <d.sabaleuski@andersenlab.com>
 * @package common\modules\api\v1\social
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'common\modules\api\v1\socialnetwork\controllers';

    /**
     * @var string Major API version. Property should be filled
     */
    public $apiVersion = '1';
}
