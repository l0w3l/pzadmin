<?php

return [
    'ip' => env('ZOMBOID_HOST_IP', 'localhost'),
    'port' => env('ZOMBOID_PORT_1', env('ZOMBOID_PORT_2', 'none')),

    'logs' => [
        'server_console' => env('ZOMBOID_SERVER_CONSOLE_LOG', base_path('docker/zomboid/storage/data/server-console.txt')),
    ],
];
