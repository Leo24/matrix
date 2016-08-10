<?php

namespace common\modules\api\v1\reasonusingmatrix;

/**
 * Class Module
 *
 * @author Dmitriy Sobolevskiy <d.sabaleuski@andersenlab.com>
 * @package common\modules\api\v1\reasonusingmatrix
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'common\modules\api\v1\reasonusingmatrix\controllers';

    /**
     * @var string Major API version. Property should be filled
     */
    public $apiVersion = '1';
}
