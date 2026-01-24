<?php

declare(strict_types=1);

return [
    'start' => [
        'active' => "🧟 Сервер активен 🌟\n\n:players\nIP: <code>:ip</code>\nPORT: <code>:port</code>\nUPTIME: :time",
        'pending' => '⌛ Сервер собирается 🚧',
        'down' => '☠️ Сервер выключен 😴',
        'backup' => "💾 Профилактика пива... 🛡️\n\nКонфигурация сервера была изменена!\nПроизводится бекап...\n\n*может занять до 5 минут",
        'unknown' => '❓ Статус сервера неизвестен 🤔',
    ],

    'keyboards' => [
        'zomboid' => [
            'status' => [
                'refreshing' => 'Обновление статуса сервера... 🧘‍♀️🍃☀️',
                'starting' => 'Запуск сервера... ᕕ( ᐛ )ᕗ',
                'restarting' => 'Перезапуск сервера... 📶🔁😵‍💫',
                'stopping' => 'Выключение сервера... 👇⏻',
            ],
            'buttons' => [
                'refresh' => '🔄',
                'start' => '▶️',
                'restart' => '↻',
                'stop' => '⏹️',
            ],
        ],
    ],
];
