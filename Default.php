<?php
defined('TYPO3_MODE') || exit('Access denied.');

// Prevent double loading
if (defined('CRON_TYPO3_ADDITIONALCONFIGURATION')) {
    return;
}

require_once __DIR__ . '/ContextLoader.php';

$GLOBALS['TYPO3_CONF_VARS']['DB']['database'] = $_ENV['MYSQL_DB'];
$GLOBALS['TYPO3_CONF_VARS']['DB']['host']     = $_ENV['MYSQL_HOST'];
$GLOBALS['TYPO3_CONF_VARS']['DB']['port']     = $_ENV['MYSQL_PORT'];
$GLOBALS['TYPO3_CONF_VARS']['DB']['username'] = $_ENV['MYSQL_USER'];
$GLOBALS['TYPO3_CONF_VARS']['DB']['password'] = $_ENV['MYSQL_PASS'];

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
