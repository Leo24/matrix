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
//    'controllerNamespace' => 'common\modules\api\v1',


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
                        'alarms' => [
                            'class' => 'common\modules\api\v1\alarms\Module'
                        ],
                        'awakenings' => [
                            'class' => 'common\modules\api\v1\awakenings\Module'
                        ],
                        'breathing' => [
                            'class' => 'common\modules\api\v1\breathing\Module'
                        ],
                        'devices' => [
                            'class' => 'common\modules\api\v1\devices\Module'
                        ],
                        'heartflex' => [
                            'class' => 'common\modules\api\v1\heartflex\Module'
                        ],
                        'heartrate' => [
                            'class' => 'common\modules\api\v1\heartrate\Module'
                        ],
                        'movements' => [
                            'class' => 'common\modules\api\v1\movements\Module'
                        ],
                        'notes' => [
                            'class' => 'common\modules\api\v1\notes\Module'
                        ],
                        'notifications' => [
                            'class' => 'common\modules\api\v1\notifications\Module'
                        ],
                        'profiles' => [
                            'class' => 'common\modules\api\v1\profiles\Module'
                        ],
                        'settings' => [
                            'class' => 'common\modules\api\v1\settings\Module'
                        ],
                        'sleepcycles' => [
                            'class' => 'common\modules\api\v1\sleepcycles\Module'
                        ],
                        'sleepquality' => [
                            'class' => 'common\modules\api\v1\sleepquality\Module'
                        ],
                        'socialnetworks' => [
                            'class' => 'common\modules\api\v1\socialnetworks\Module'
                        ],
                        'stress' => [
                            'class' => 'common\modules\api\v1\stress\Module'
                        ],
                        'users' => [
                            'class' => 'common\modules\api\v1\users\Module'
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
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
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
//        'errorHandler' => [
//            'errorAction' => 'site/error',
//        ],

//        'user' => [
//            'identityClass' => 'common\modules\api\v1\authorization\models\auth',
//            'enableAutoLogin' => false,
            // Set to null to not redirect unathorized requests
            //'loginUrl' => null,
//        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
//            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => [
                        'v1/alarms' => '/api/v1/alarms/alarms',
                        'v1/login' => '/api/v1/authorization/authorization/'
                    ],
                    'prefix' => 'api/',
                ],

            ],
        ],

        'request' => [
            'baseUrl' => '',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],

    ],
    'params' => $params,
];
