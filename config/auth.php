<?php

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'), // Default guard untuk web
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        // Guard API untuk Sanctum, gunakan provider siswas
        'api' => [
            'driver' => 'sanctum',
            'provider' => 'siswas',
            //'hash' => false, // Optional, sesuai kebutuhan
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => env(App\Models\UserModel::class),
        ],

        // Provider khusus untuk model Siswa
        'siswas' => [
            'driver' => 'eloquent',
            'model' => App\Models\Siswa::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
