<?php

return array(
    'SYS' => array(
        'trustedHostsPattern' => '.*',
        'doNotCheckReferer'   => TRUE,
    ),
    'GFX' => array(
        'im_path'      => '/usr/bin/',
        'im_path_lzw'  => '/usr/bin/',
        'im_version_5' => 'gm',
    ),
    'DB' => array(
        'database' => $_ENV['MYSQL_DB']   ?: 'typo3',
        'host'     => $_ENV['MYSQL_HOST'] ?: 'mysql',
        'port'     => $_ENV['MYSQL_PORT'] ?: '3306',
        'username' => $_ENV['MYSQL_USER'] ?: 'dev',
        'password' => $_ENV['MYSQL_PASS'] ?: 'dev',
        'Connections' => array(
            'Default' => array(
                'dbname'   => $_ENV['MYSQL_DB']   ?: 'typo3',
                'host'     => $_ENV['MYSQL_HOST'] ?: 'mysql',
                'port'     => $_ENV['MYSQL_PORT'] ?: '3306',
                'user'     => $_ENV['MYSQL_USER'] ?: 'dev',
                'password' => $_ENV['MYSQL_PASS'] ?: 'dev',
            ),
        ),
    ),
);
