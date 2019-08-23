<?php

return array(
    'SYS' => array(
        'trustedHostsPattern' => '.*',
        'doNotCheckReferer'   => TRUE,
    ),
    'GFX' => array(
        'processor_path'      => '/usr/bin/',
        'processor_path_lzw'  => '/usr/bin/',
        'processor' => 'GraphicsMagick',
    ),
    'DB' => array(
        'database' => $_ENV['MYSQL_DB']   ?: 'typo3',
        'host'     => $_ENV['MYSQL_HOST'] ?: 'mysql',
        'port'     => $_ENV['MYSQL_PORT'] ?: '3306',
        'username' => $_ENV['MYSQL_USER'] ?: 'dev',
        'password' => $_ENV['MYSQL_PASS'] ?: 'dev',
        'Connections' => array(
            'Default' => array(
                'driver'   => 'mysqli',
                'dbname'   => $_ENV['MYSQL_DB']   ?: 'typo3',
                'host'     => $_ENV['MYSQL_HOST'] ?: 'mysql',
                'port'     => $_ENV['MYSQL_PORT'] ?: '3306',
                'user'     => $_ENV['MYSQL_USER'] ?: 'dev',
                'password' => $_ENV['MYSQL_PASS'] ?: 'dev',
            ),
        ),
    ),
);
