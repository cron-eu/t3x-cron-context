<?php
defined('TYPO3_MODE') || exit('Access denied.');

// Prevent double loading
if (defined('CRON_TYPO3_ADDITIONALCONFIGURATION')) {
    return;
}

require_once __DIR__ . '/ContextLoader.php';

$confLoader = new \Cron\CronContext\ContextLoader();
$confLoader
        // Add EXT:cron_context default context configuration
    ->addContextConfiguration(PATH_site . '/typo3conf/ext/cron_context/Configuration/')
        // Add project context configuration
    ->addContextConfiguration(PATH_site . '/typo3conf/AdditionalConfiguration')
        // Add local configuration
    ->addConfiguration(PATH_site . '/typo3conf/AdditionalConfiguration/Local.php')
        // Load configuration files (maybe cached)
    ->loadConfiguration()
        // Add context name to sitename (if in development context)
    ->appendContextNameToSitename();
unset($confLoader);
