<?php

return [
    'adminEmail' => 'admin@example.com',
    'secretJWT' => '34U*gd^&G*&D#Ge',
    'algorithmJWT' => 'HS256',
    'tokenExpireDays' => 7,
    'logger' => [
        'error_emfit_data' => [
            'category' => 'error_emfit_data'
        ],
        'register_user' => [
            'category' => 'register_user'
        ],
        'emfit_data' => [
            'category' => 'emfit_data'
        ]
    ]
];
