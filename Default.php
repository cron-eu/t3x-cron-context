<?php
defined('TYPO3') || die('Access denied.');

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
        if (!empty(getenv($env))) {
            $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['dbname'] = getenv($env);
            $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['driver'] = 'mysqli';
        }
    }
    if (!empty(getenv('DB_DRIVER'))) {
        $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['driver'] = getenv('DB_DRIVER');
    }
    foreach (['MYSQL_HOST', 'DB_HOST'] as $env) {
        if (!empty(getenv($env))) {
            $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['host'] = getenv($env);
        }
    }
    foreach (['MYSQL_PORT', 'DB_PORT'] as $env) {
        if (!empty(getenv($env))) {
            $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['port'] = getenv($env);
        }
    }
    foreach (['MYSQL_USER', 'DB_USER', 'DB_USERNAME'] as $env) {
        if (!empty(getenv($env))) {
            $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['user'] = getenv($env);
        }
    }
    foreach (['MYSQL_PASS', 'DB_PASS', 'DB_PASSWORD'] as $env) {
        if (!empty(getenv($env))) {
            $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['password'] = getenv($env);
        }
    }

    $confLoader = new \Cron\CronContext\ContextLoader();
    $confLoader
        // Add EXT:cron_context default context configuration
        ->addContextConfiguration(__DIR__ . '/Configuration/')
        // Add project context configuration
        ->addContextConfiguration(\TYPO3\CMS\Core\Core\Environment::getConfigPath() . '/system/additional')
        // Add local configuration
        ->addConfiguration(\TYPO3\CMS\Core\Core\Environment::getConfigPath() . '/system/additional/local.php')
        // Load configuration files
        ->loadConfiguration()
        // Add context name to sitename (if in development context)
        ->appendContextNameToSitename();
});
