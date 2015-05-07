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
        'database' => 'typo3',
        'host'     => 'mysql',
        'port'     => '3306',
        'username' => 'dev',
        'password' => 'dev',
    ),
);
