<?php

return [
    'adminEmail' => 'admin@example.com',
    'secretJWT' => '34U*gd^&G*&D#Ge',
    'algorithmJWT' => 'HS256',
    'tokenExpireDays' => 7,
    'logger' => [
        'synchronize_emfit_data' => [
            'category' => 'emfit_data'
        ],
        'register_user' => [
            'category' => 'register_user'
        ]
    ]
];
