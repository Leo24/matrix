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
                        'emfit'         => [
                            'class' => 'common\modules\api\v1\emfit\Module'
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
                    'categories'     => ['error_emfit_data'],
                    'logVars'        => [],
                    'logFile'        => '@app/runtime/logs/error_emfit_data.log',
                    'exportInterval' => 1,
                    'maxFileSize'    => 1024 * 2,
                    'maxLogFiles'    => 20
                ],
                [
                    'class'          => 'yii\log\FileTarget',
                    'levels'         => ['error', 'trace'],
                    'categories'     => ['register_user'],
                    'logVars'        => [],
                    'logFile'        => '@app/runtime/logs/register_user.log',
                    'exportInterval' => 1,
                    'maxFileSize'    => 1024 * 2,
                    'maxLogFiles'    => 20
                ],
                [
                    'class'          => 'yii\log\FileTarget',
                    'levels'         => ['error', 'trace'],
                    'categories'     => ['emfit_data'],
                    'logVars'        => [],
                    'logFile'        => '@app/runtime/logs/emfit_data.log',
                    'exportInterval' => 1,
                    'maxFileSize'    => 1024 * 2,
                    'maxLogFiles'    => 20
                ]
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
                        'synchronize/emfitdata' => 'api/v1/emfit/synchronize'
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
                    'class' => 'yii\rest\UrlRule',
                    'pluralize' => false,
                    'prefix' => 'api/v1/',
                    'controller' => [
                        'report' => 'api/v1/report/backend/report',
                        'report/sleep-quality' => 'api/v1/report/backend/report',
                        'report/sleep-cycles' => 'api/v1/report/backend/report',
                        'report/stress' => 'api/v1/report/backend/report',
                        'report/matrix-averages' => 'api/v1/report/backend/report',
                        'report/heart-rate' => 'api/v1/report/backend/report',
                        'report/heart-health' => 'api/v1/report/backend/report',
                        'report/breathing' => 'api/v1/report/backend/report',
                    ],
                    'patterns' => [
                        'GET sleep-cycles' => 'sleep-cycles',
                        'GET matrix-averages' => 'matrix-averages',
                        'GET sleep-quality' => 'sleep-quality',
                        'GET stress' => 'stress',
                        'GET heart-rate' => 'heart-rate',
                        'GET heart-health' => 'heart-health',
                        'GET breathing' => 'breathing'
                    ]

                ],
                [
                    'class'      => 'yii\rest\UrlRule',
                    'prefix'     => 'api/v1/',
                    'controller' => ['devices' => 'api/v1/user/backend/device'],
                    'except'       => ['delete']
                ],
                [
                    'class'      => 'yii\rest\UrlRule',
                    'prefix'     => 'api/v1/',
                    'controller' => ['healths' => 'api/v1/user/backend/health'],
                    'except'       => ['delete']
                ],
                [
                    'class'      => 'yii\rest\UrlRule',
                    'prefix'     => 'api/v1/',
                    'controller' => ['profiles' => 'api/v1/user/backend/profile'],
                    'only'       => ['view', 'update']
                ],
                [
                    'class'      => 'yii\rest\UrlRule',
                    'prefix'     => 'api/v1/',
                    'controller' => ['sleeping-positions' => 'api/v1/user/backend/sleeping-position'],
                    'except'     => ['delete']
                ],
                [
                    'class'      => 'yii\rest\UrlRule',
                    'prefix'     => 'api/v1/',
                    'controller' => ['reason-using-matrix' => 'api/v1/user/backend/reason-using-matrix'],
                    'except'     => ['delete']
                ],
                [
                    'class'      => 'yii\rest\UrlRule',
                    'pluralize'  => false,
                    'prefix'     => 'api/v1/',
                    'controller' => [
                        'users'          => 'api/v1/user/backend/user',
                        'notifications'  => 'api/v1/notification/backend/notification',
                        'socialnetworks' => 'api/v1/user/backend/social-network',
                    ],
                ],
            ],
        ],
    ],
    'params' => $params,
];
