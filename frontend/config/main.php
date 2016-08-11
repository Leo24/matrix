<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id'                  => 'app-frontend',
    'basePath'            => dirname(__DIR__),
    'bootstrap'           => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'homeUrl'             => '/',
    'modules'             => [
        'api' => [
            'class'   => 'common\modules\api\Module',
            'modules' => [
                'v1' => [
                    'class'   => 'common\modules\api\v1\Module',
                    'modules' => [
                        'authorization'     => [
                            'class' => 'common\modules\api\v1\authorization\Module'
                        ],
                        'sleepquality'      => [
                            'class' => 'common\modules\api\v1\sleepquality\Module'
                        ],
                        'report'            => [
                            'class' => 'common\modules\api\v1\report\Module'
                        ],
                        'notification'      => [
                            'class' => 'common\modules\api\v1\notification\Module'
                        ],
                        'settings'          => [
                            'class' => 'common\modules\api\v1\settings\Module'
                        ],
                        'user'              => [
                            'class' => 'common\modules\api\v1\user\Module'
                        ],
                        'synchronize'         => [
                            'class' => 'common\modules\api\v1\synchronize\Module'
                        ],
                    ]
                ],
            ],
        ],
    ],
    'components'          => [
        'response'   => [
            'format'  => yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8',
        ],
        'request'    => [
            'baseUrl'                => '/',
            'class'                  => '\yii\web\Request',
            'enableCookieValidation' => false,
            'parsers'                => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'user'       => [
            'identityClass'   => 'common\modules\api\v1\user\models\User',
            'enableSession'   => false,
            'enableAutoLogin' => false,
        ],
        'log'        => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'   => 'yii\log\FileTarget',
                    'levels'  => ['error', 'warning']
                ],
                [
                    'class'          => 'yii\log\FileTarget',
                    'levels'         => ['error', 'trace'],
                    'categories'     => ['emfit_data'],
                    'logVars'        => [],
                    'logFile'        => '@app/runtime/logs/synchronize_emfit_data.log',
                    'exportInterval' => 1,
                    'maxFileSize'    => 1024 * 2,
                    'maxLogFiles'    => 20
                ],

            ],
        ],
        'urlManager' => [
            'enablePrettyUrl'     => true,
            'enableStrictParsing' => false,
            'showScriptName'      => false,
            'rules'               => [
                [
                    'class'      => 'yii\rest\UrlRule',
                    'prefix'     => 'api/v1/',
                    'controller' => ['user' => 'api/v1/authorization/frontend/authorization'],
                    'patterns'   => ['POST login' => 'login']
                ],
                [
                    'class'      => 'yii\rest\UrlRule',
                    'prefix'     => 'api/v1/',
                    'controller' => ['user' => 'api/v1/authorization/backend/authorization'],
                    'patterns'   =>
                    [
                        'GET logout'  => 'logout',
                        'GET refresh' => 'refresh'
                    ]
                ],
                [
                    'class'      => 'yii\rest\UrlRule',
                    'prefix'     => 'api/v1/',
                    'controller' => ['user/register' => 'api/v1/user/frontend/user'],
                    'patterns'   => ['POST' => 'register']
                ],
                [
                    'class'      => 'yii\rest\UrlRule',
                    'prefix'     => 'api/v1/',
                    'controller' => ['user/password' => 'api/v1/user/backend/user'],
                    'patterns'   => ['PUT,PATCH' => 'password']
                ],
                [
                    'class'      => 'yii\rest\UrlRule',
                    'prefix'     => 'api/v1/',
                    'controller' => ['avatar/upload' => 'api/v1/user/backend/avatar'],
                    'patterns'   => ['POST' => 'upload']
                ],
                [
                    'class'      => 'yii\rest\UrlRule',
                    'prefix'     => 'api/v1/',
                    'controller' => [
                        'synchronize/emfitdata' => 'api/v1/synchronize/emfit-data'
                    ],
                    'patterns'   => ['POST' => 'save-data'],
                    'except'     => ['update', 'view', 'delete', 'create', 'view']
                ],
                [
                    'class'      => 'yii\rest\UrlRule',
                    'prefix'     => 'api/v1/',
                    'controller' => ['settings/notifications' => 'api/v1/settings/backend/notification'],
                    'except'     => ['create', 'delete', 'view']
                ],
                [
                    'class'      => 'yii\rest\UrlRule',
                    'prefix'     => 'api/v1/',
                    'controller' => ['blocks' => 'api/v1/authorization/backend/block'],
                    'only'       => ['index']
                ],
                [
                    'class'      => 'yii\rest\UrlRule',
                    'pluralize'  => false,
                    'prefix'     => 'api/v1/',
                    'controller' => [
                        'profiles'       => 'api/v1/user/backend/profile',
                        'users'          => 'api/v1/user/backend/user',
                        'notifications'  => 'api/v1/notification/backend/notification',
                        'healths'        => 'api/v1/user/backend/health',
                        'devices'        => 'api/v1/user/backend/device',
                        'socialnetworks' => 'api/v1/user/backend/social-network',
                        'sleeping-positions' => 'api/v1/user/backend/sleeping-position',
                        'reason-using-matrix' => 'api/v1/user/backend/reason-using-matrix',
                    ],
                ],
            ],
        ],
    ],
    'params' => $params,
];
