<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache'            => [
            'class' => 'yii\caching\FileCache',
        ],
        'db'               => [
            'class'    => 'yii\db\Connection',
            'dsn'      => 'mysql:host=localhost;dbname=matrix',
            'username' => 'matrix',
            'password' => 'matrix123321',
            'charset'  => 'utf8',
        ],
        'apns'             => [
            'class'       => 'bryglen\apnsgcm\Apns',
            'environment' => \bryglen\apnsgcm\Apns::ENVIRONMENT_SANDBOX,
            'pemFile'     => dirname(__FILE__) . '/apnssert/apns-dev.pem',
            'options'     => [
                'sendRetryTimes' => 5
            ]
        ],
    ],
];
