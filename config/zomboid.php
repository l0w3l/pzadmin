<?php

$dockerEnvContent = file_get_contents(base_path('/docker/.env'));
if ($dockerEnvContent === false) {
    $dockerEnvContent = '';
}

$dockerEnv = (new \Symfony\Component\Dotenv\Dotenv)->parse($dockerEnvContent);

return [
    'ip' => $dockerEnv['ZOMBOID_HOST_IP'] ?? 'localhost',
    'port' => $dockerEnv['ZOMBOID_PORT_1'] ?? ($dockerEnv['ZOMBOID_PORT_2'] ?? 'none'),
    'docker' => [
        'name' => ($dockerEnv['APP_SERVER_NAME'] ?? 'pzadmin').'_zomboid',
        'socket' => 'zomboid',
    ],

    'rcon' => [
        'port' => (int) ($dockerEnv['ZOMBOID_RCON_PORT'] ?? 27015),
        'password' => $dockerEnv['ZOMBOID_RCON_PASSWORD'] ?? null,
    ],

    'logs' => [
        'server_console' => $dockerEnv['ZOMBOID_SERVER_CONSOLE_LOG'] ?? base_path('docker/zomboid/storage/data/server-console.txt'),
    ],
];
