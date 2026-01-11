<?php

return [
    'ip' => env('ZOMBOID_HOST_IP', 'localhost'),
    'port' => env('ZOMBOID_PORT_1', env('ZOMBOID_PORT_2', 'none')),

    'steam_key' => env('ZOMBOID_STEAM_KEY'),

    'rcon' => [
        'port' => (int) env('ZOMBOID_RCON_PORT', 27015),
        'password' => env('ZOMBOID_RCON_PASSWORD'),
    ],

    'logs' => [
        'server_console' => env('ZOMBOID_SERVER_CONSOLE_LOG', base_path('docker/zomboid/storage/data/server-console.txt')),
    ],
];
