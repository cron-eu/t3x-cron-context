<?php

// Disable frontend caching (single hit caching)
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cache_pagesection']['options'] =
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cache_hash']['options'] =
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cache_pages']['options'] = [];
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cache_pagesection']['backend'] =
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cache_hash']['backend'] =
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cache_pages']['backend'] =
    'TYPO3\\CMS\\Core\\Cache\\Backend\\TransientMemoryBackend';

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
        'sessionTimeout'      => '360000'
    ],
    'FE'  => [
        'disableNoCacheParameter' => false,
        'debug'                   => true,
    ],
];
