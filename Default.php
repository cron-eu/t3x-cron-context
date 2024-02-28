<?php
defined('TYPO3_MODE') || exit('Access denied.');

// Prevent double loading
if (defined('CRON_TYPO3_ADDITIONALCONFIGURATION')) {
    return;
}

require_once __DIR__ . '/ContextLoader.php';

call_user_func(function() {
    if (getenv('IS_DDEV_PROJECT') == 'true') {
        // Hardcode defaults for a ddev installation
        $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['dbname'] = 'db';
        $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['host'] = 'db';
        $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['user'] = 'db';
        $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['password'] = 'db';
    }

    foreach (['MYSQL_DB', 'DB_NAME', 'DB_DATABASE'] as $env) {
        if (isset($_ENV[$env])) {
            $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['dbname'] = $_ENV[$env];
        }
    }
    foreach (['MYSQL_HOST', 'DB_HOST'] as $env) {
        if (isset($_ENV[$env])) {
            $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['host'] = $_ENV[$env];
        }
    }
    foreach (['MYSQL_PORT', 'DB_PORT'] as $env) {
        if (isset($_ENV[$env])) {
            $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['port'] = $_ENV[$env];
        }
    }
    foreach (['MYSQL_USER', 'DB_USER', 'DB_USERNAME'] as $env) {
        if (isset($_ENV[$env])) {
            $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['user'] = $_ENV[$env];
        }
    }
    foreach (['MYSQL_PASS', 'DB_PASS', 'DB_PASSWORD'] as $env) {
        if (isset($_ENV[$env])) {
            $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['password'] = $_ENV[$env];
        }
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
});
