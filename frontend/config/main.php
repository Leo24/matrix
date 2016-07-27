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


    'modules'             => [
        'api' => [
            'class'   => 'common\modules\api\Module',
            'modules' => [
                'v1' => [
                    'class' => 'common\modules\api\v1\Module',
                    'modules' => [
                        'authorization' => [
                            'class' => 'common\modules\api\v1\authorization\Module'
                        ],
                        'alarm' => [
                            'class' => 'common\modules\api\v1\alarm\Module'
                        ],
                        'awakening' => [
                            'class' => 'common\modules\api\v1\awakening\Module'
                        ],
                        'breathing' => [
                            'class' => 'common\modules\api\v1\breathing\Module'
                        ],
                        'device' => [
                            'class' => 'common\modules\api\v1\device\Module'
                        ],
                        'heartflex' => [
                            'class' => 'common\modules\api\v1\heartflex\Module'
                        ],
                        'heartrate' => [
                            'class' => 'common\modules\api\v1\heartrate\Module'
                        ],
                        'movement' => [
                            'class' => 'common\modules\api\v1\movement\Module'
                        ],
                        'note' => [
                            'class' => 'common\modules\api\v1\note\Module'
                        ],
                        'notification' => [
                            'class' => 'common\modules\api\v1\notification\Module'
                        ],
                        'profile' => [
                            'class' => 'common\modules\api\v1\profile\Module'
                        ],
                        'setting' => [
                            'class' => 'common\modules\api\v1\setting\Module'
                        ],
                        'sleepcycle' => [
                            'class' => 'common\modules\api\v1\sleepcycle\Module'
                        ],
                        'sleepquality' => [
                            'class' => 'common\modules\api\v1\sleepquality\Module'
                        ],
                        'socialnetwork' => [
                            'class' => 'common\modules\api\v1\socialnetwork\Module'
                        ],
                        'stress' => [
                            'class' => 'common\modules\api\v1\stress\Module'
                        ],
                        'user' => [
                            'class' => 'common\modules\api\v1\user\Module'
                        ],
                    ]
                ],
            ],
        ],
        'Module' => [
            'class' => 'common\modules\api\v2\Module',
        ],
    ],


    'components' => [
        'request' => [
            'class' => '\yii\web\Request',
            'enableCookieValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8',
        ],
        'user' => [
            'identityClass' => 'common\modules\api\v1\auth\models\User',
            'enableSession' => false,
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
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'prefix' => 'api/v1/',
                    'controller' => ['user/logout' => 'api/v1/authorization/authorization'],
                    'patterns' => ['GET' => 'logout']
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'prefix' => 'api/v1/',
                    'controller' => ['user/login' => 'api/v1/authorization/authorization'],
                    'patterns' => ['POST' => 'login']
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'prefix' => 'api/v1/',
                    'controller' => ['user/refresh' => 'api/v1/authorization/authorization'],
                    'patterns' => ['GET' => 'refresh']
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'prefix' => 'api/v1/',
                    'controller' => ['user/register' => 'api/v1/user/user'],
                    'patterns' => ['POST' => 'register']
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => [
                        'api/v1/note/note',
                        'api/v1/notification/notification',
                        'api/v1/user/alarm',
                        'api/v1/user/awakening',
                        'api/v1/user/breathing',
                        'api/v1/user/device',
                        'api/v1/user/heartflex',
                        'api/v1/user/heartrate',
                        'api/v1/user/movement',
                        'api/v1/user/profile',
                        'api/v1/user/setting',
                        'api/v1/user/sleepcycle',
                        'api/v1/user/sleepquality',
                        'api/v1/user/socialnetwork',
                        'api/v1/user/stress',
                        'api/v1/user/user',
                    ],
                    'tokens' => [
                        '{id}' => '<id:\\w+>',
                        '{count}' => '<count:\\w+>',
                    ],
                    'extraPatterns' => [
                        'POST' => 'create', // 'xxxxx' refers to 'actionXxxxx'
                        'PUT {id}' => 'update',
                        'PATCH {id}' => 'update',
                        'DELETE {id}' => 'delete',
                        'GET {id}' => 'view',
                        'GET {count}' => 'index',
                    ],
                ],

            ],
        ],


    ],
    'params' => $params,
];
