# Cron TYPO3_CONTEXT Configuration

## Installation

Install extension via `composer` and add `typo3conf/AdditionalConfiguration.php` (or use symlinks):

    <?php
    defined('TYPO3_MODE') || exit('Access denied.');

    require_once __DIR__ . '/ext/cron_context/Default.php';

Copy `EXT:cron_context/Examples/AdditionalConfiguration/` to `typo3conf/AdditionalConfiguration/`

Hint: You don't need to install this extension inside TYPO3 CMS.

## Configuration

When using `EXT:cron_context/Default.php` following configuration directories are used (in this order):

- `EXT:cron_context/Configuration/`
- `typo3conf/AdditionalConfiguration/`

### Context examples

TYPO3_CONTEXT=Production (default):
- `typo3conf/AdditionalConfiguration/Production.php`

TYPO3_CONTEXT=Testing (eg. for Unit tests):
- `typo3conf/AdditionalConfiguration/Testing.php`

TYPO3_CONTEXT=Development (for development):
- `typo3conf/AdditionalConfiguration/Development.php`

TYPO3_CONTEXT=Development/Docker (for development inside docker boilerplate):
- `typo3conf/AdditionalConfiguration/Development.php`
- `typo3conf/AdditionalConfiguration/Development/Docker.php`

TYPO3_CONTEXT=Production/Preview (for preview):
- `typo3conf/AdditionalConfiguration/Development.php`
- `typo3conf/AdditionalConfiguration/Development/Preview.php`

TYPO3_CONTEXT=Production/Live/Server4711 (specific live server configuration):
- `typo3conf/AdditionalConfiguration/Development.php`
- `typo3conf/AdditionalConfiguration/Development/Live.php`
- `typo3conf/AdditionalConfiguration/Development/Live/Server4711.php`

## Environment variables

cron_context will read the TYPO3 DB credentials from the following environment variables if present:

* MYSQL_DB
* MYSQL_HOST
* MYSQL_PORT
* MYSQL_USER
* MYSQL_PASS

## Advanced usage

If you don't want to use `EXT:cron_context/Configuration/` you can customize your own loading in `typo3conf/AdditionalConfiguration.php`

    <?php
    defined('TYPO3_MODE') || exit('Access denied.');

    // Prevent double loading
    if (defined('CRON_TYPO3_ADDITIONALCONFIGURATION')) {
        return;
    }

    require_once __DIR__ . '/ext/cron_context/ContextLoader.php';

    $confLoader = new \Cron\CronContext\ContextLoader();
    $confLoader
            // Add project context configuration
        ->addContextConfiguration(\TYPO3\CMS\Core\Core\Environment::getPublicPath() . '/typo3conf/AdditionalConfiguration')
            // Add local configuration
        ->addConfiguration(\TYPO3\CMS\Core\Core\Environment::getPublicPath() . '/typo3conf/AdditionalConfiguration/Local.php')
            // Use TYPO3_CONF caching (only in production context)
        ->useCacheInProduction()
            // Load configuration files (maybe cached)
        ->loadConfiguration()
            // Add context name to sitename (if in development context)
        ->appendContextNameToSitename();
    unset($confLoader);


For extension configuration manipulation:

    <?php

    $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['foobar']['key'] => 'value';
