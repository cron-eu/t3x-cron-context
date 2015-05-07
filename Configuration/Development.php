<?php

// Disable frontend caching (single hit caching)
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cache_pagesection']['options'] =
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cache_hash']['options'] =
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cache_pages']['options'] = array();
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cache_pagesection']['backend'] =
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cache_hash']['backend'] =
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['cache_pages']['backend'] =
    'TYPO3\\CMS\\Core\\Cache\\Backend\\TransientMemoryBackend';

return array(
    'SYS' => array(
        'trustedHostsPattern'  => '.*',
        'devIPmask'            => '*',
        'sqlDebug'             => 1,
        'displayErrors'        => 1,
        'enableDeprecationLog' => 'file',
        'systemLogLevel'       => 0,
    ),
    'BE'  => array(
        'installToolPassword' => '$P$Cp1V/nMasgbH9PTywUhYxaBxY4tRZ1.', // dev
        'debug'               => true,
        'sessionTimeout'      => '360000'
    ),
    'FE'  => array(
        'disableNoCacheParameter' => false,
        'debug'                   => true,
    ),
);
