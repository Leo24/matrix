<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'homeUrl' => '/',
    'modules' => [
        'api' => [
            'class'   => 'common\modules\api\Module',
            'modules' => [
                'v1' => [
                    'class' => 'common\modules\api\v1\Module',
                    'modules' => [
                        'authorization' => [
                            'class' => 'common\modules\api\v1\authorization\Module'
                        ],
                        'block' => [
                            'class' => 'common\modules\api\v1\block\Module'
                        ],
                        'profile' => [
                            'class' => 'common\modules\api\v1\profile\Module'
                        ],
                        'sleepquality' => [
                            'class' => 'common\modules\api\v1\sleepquality\Module'
                        ],
                        'socialnetwork' => [
                            'class' => 'common\modules\api\v1\socialnetwork\Module'
                        ],
                        'reasonusingmatrix' => [
                            'class' => 'common\modules\api\v1\reasonusingmatrix\Module'
                        ],
                        'sleepingposition' => [
                            'class' => 'common\modules\api\v1\sleepingposition\Module'
                        ],
                        'device' => [
                            'class' => 'common\modules\api\v1\device\Module'
                        ],
                        'notification' => [
                            'class' => 'common\modules\api\v1\notification\Module'
                        ],
                        'settings' => [
                            'class' => 'common\modules\api\v1\settings\Module'
                        ],
                        'user' => [
                            'class' => 'common\modules\api\v1\user\Module'
                        ],
                        'emfitdata' => [
                            'class' => 'common\modules\api\v1\emfitdata\Module'
                        ],
                    ]
                ],
            ],
        ],
    ],
    'components' => [
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8',
        ],
        'request' => [
            'baseUrl' => '/',
            'class' => '\yii\web\Request',
            'enableCookieValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'user' => [
            'identityClass' => 'common\modules\api\v1\user\models\User',
            'enableSession' => false,
            'enableAutoLogin' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'prefix' => 'api/v1/',
                    'controller' => ['user' => 'api/v1/authorization/frontend/authorization'],
                    'patterns' => [
                        'POST login' => 'login',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'prefix' => 'api/v1/',
                    'controller' => ['user' => 'api/v1/authorization/backend/authorization'],
                    'patterns' => [
                        'GET logout' => 'logout',
                        'GET refresh' => 'refresh'
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'prefix' => 'api/v1/',
                    'controller' => ['user/register' => 'api/v1/user/frontend/user'],
                    'patterns' => ['POST' => 'register']
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'prefix' => 'api/v1/',
                    'controller' => ['user/password' => 'api/v1/user/backend/user'],
                    'patterns' => ['PUT,PATCH' => 'password']
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'prefix' => 'api/v1/',
                    'controller' => ['avatar/upload' => 'api/v1/profile/backend/avatar'],
                    'patterns' => ['POST' => 'upload']
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'prefix' => 'api/v1/',
                    'controller' => ['emfit/emfitdata' => 'api/v1/emfitdata/emfitdata'],
                    'patterns' => ['POST' => 'get-data']
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'prefix' => 'api/v1/',
                    'controller' => ['emfitdata' => 'api/v1/emfitdata/emfitdata'],
                    'patterns' => [ 'GET' => 'parse-data',]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'prefix' => 'api/v1/',
                    'controller' => ['notifications' => 'api/v1/notification/backend/notification',],
                    'except' => ['index', 'create'],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'pluralize' => false,
                    'prefix' => 'api/v1/',
                    'controller' => [
                        'profiles' => 'api/v1/profile/backend/profile',
                        'profiles/sleep/quality' => 'api/v1/sleepquality/backend/sleepquality',
                        'users' => 'api/v1/user/backend/user',
                        'socialnetworks' => 'api/v1/socialnetwork/backend/socialnetwork',
                        'notifications' => 'api/v1/notification/backend/notification',
                        'devices' => 'api/v1/device/backend/device',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'prefix' => 'api/v1/',
                    'controller' => [
                        'settings/notifications' => 'api/v1/settings/backend/notification'
                    ],
                    'except' => ['create', 'delete', 'view']
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'prefix' => 'api/v1/',
                    'controller' => ['notifications' => 'api/v1/notification/backend/notification',],
                    'except' => ['index', 'create'],
                ],
            ],
        ],
    ],
    'params' => $params,
];
