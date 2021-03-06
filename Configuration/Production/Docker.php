<?php

return [
    'DB' => [
        'Connections' => [
            'Default' => [
                'driver'   => 'mysqli',
                'dbname'   => $_ENV['MYSQL_DB']   ?? 'typo3',
                'host'     => $_ENV['MYSQL_HOST'] ?? 'mysql',
                'port'     => $_ENV['MYSQL_PORT'] ?? '3306',
                'user'     => $_ENV['MYSQL_USER'] ?? 'dev',
                'password' => $_ENV['MYSQL_PASS'] ?? 'dev',
            ],
        ],
    ],
];
