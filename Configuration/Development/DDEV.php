<?php

return array_merge(include __DIR__ . '/Docker.php',  [
    'MAIL' => [
        'transport' => 'smtp',
        'transport_smtp_server' => 'localhost:1025'
    ],
    'SYS' => [
        'reverseProxyHeaderMultiValue' => 'none',
        'reverseProxyIP' =>  '*',
        'reverseProxySSL' => '*',
    ],
]);
