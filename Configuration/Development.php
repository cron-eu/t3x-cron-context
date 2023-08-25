<?php

// Disable frontend caching (single hit caching)
$configureCache = function(string $index) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$index]['options'] = [];
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$index]['backend'] =
        'TYPO3\\CMS\\Core\\Cache\\Backend\\TransientMemoryBackend';
};

$configureCache('pagesection');
$configureCache('hash');
$configureCache('pages');

$logWriterConf = [
    'TYPO3' => [
        'CMS' => [
            'deprecations' => [
                'writerConfiguration' => [
                    \TYPO3\CMS\Core\Log\LogLevel::NOTICE => [
                        \TYPO3\CMS\Core\Log\Writer\FileWriter::class => [
                            'logFileInfix' => 'deprecations'
                        ],
                    ],
                ],
            ],
        ],
    ],
];
$GLOBALS['TYPO3_CONF_VARS']['LOG'] = array_replace_recursive($GLOBALS['TYPO3_CONF_VARS']['LOG'], $logWriterConf);

return [
    'SYS' => [
        'trustedHostsPattern'  => '.*',
        'devIPmask'            => '*',
        'sqlDebug'             => 1,
        'displayErrors'        => 1,
        'systemLogLevel'       => 0,
    ],
    'BE'  => [
        'installToolPassword' => '$P$Cp1V/nMasgbH9PTywUhYxaBxY4tRZ1.', // dev
        'debug'               => true,
        'sessionTimeout'      => '360000',
        'requireMfa'          => 0,
        'loginRateLimit'      => 0,
    ],
    'FE'  => [
        'disableNoCacheParameter' => false,
        'debug'                   => true,
        'loginRateLimit'          => 0,
    ],
];
