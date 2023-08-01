<?php

return [
    'MAIL' => [
        'transport' => 'smtp',
        'transport_smtp_server' => 'mail:1025'
    ],
    'SYS' => [
        'trustedHostsPattern' => '.*'
    ],
    'GFX' => [
        'processor_path'      => '/usr/bin/',
        'processor_path_lzw'  => '/usr/bin/',
        'processor' => 'GraphicsMagick',
    ],
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
