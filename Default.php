<?php
defined('TYPO3_MODE') || exit('Access denied.');

// Prevent double loading
if (defined('CRON_TYPO3_ADDITIONALCONFIGURATION')) {
    return;
}

require_once __DIR__ . '/ContextLoader.php';

if (isset($_ENV['MYSQL_DB'])) {
    $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['dbname'] = $_ENV['MYSQL_DB'];
}
if (isset($_ENV['MYSQL_HOST'])) {
    $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['host'] = $_ENV['MYSQL_HOST'];
}
if (isset($_ENV['MYSQL_PORT'])) {
    $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['port'] = $_ENV['MYSQL_PORT'];
}
if (isset($_ENV['MYSQL_USER'])) {
    $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['user'] = $_ENV['MYSQL_USER'];
}
if (isset($_ENV['MYSQL_PASS'])) {
    $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['password'] = $_ENV['MYSQL_PASS'];
}

$confLoader = new \Cron\CronContext\ContextLoader();
$confLoader
        // Add EXT:cron_context default context configuration
    ->addContextConfiguration(\TYPO3\CMS\Core\Core\Environment::getPublicPath() . '/typo3conf/ext/cron_context/Configuration/')
        // Add project context configuration
    ->addContextConfiguration(\TYPO3\CMS\Core\Core\Environment::getPublicPath() . '/typo3conf/AdditionalConfiguration')
        // Add local configuration
    ->addConfiguration(\TYPO3\CMS\Core\Core\Environment::getPublicPath() . '/typo3conf/AdditionalConfiguration/Local.php')
        // Load configuration files
    ->loadConfiguration()
        // Add context name to sitename (if in development context)
    ->appendContextNameToSitename();
unset($confLoader);
