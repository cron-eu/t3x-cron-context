# Cron TYPO3_CONTEXT Configuration

## Installation

Install extension via `composer` and add `config/system/additional.php` (or use symlinks):

```
<?php
defined('TYPO3') || die('Access denied.');

require_once \TYPO3\CMS\Core\Core\Environment::getProjectPath() . '/vendor/cron-eu/cron-context/Default.php';
```

Copy `EXT:cron_context/Examples/additional/` to `config/system/additional/`

Hint: You don't need to install this extension inside TYPO3 CMS.

## Configuration

When using `EXT:cron_context/Default.php` following configuration directories are used (in this order):

- `EXT:cron_context/Configuration/`
- `config/system/additional/`

### Context examples

TYPO3_CONTEXT=Production (default):
- `config/system/additional/Production.php`

TYPO3_CONTEXT=Testing (eg. for Unit tests):
- `config/system/additional/Testing.php`

TYPO3_CONTEXT=Development (for development):
- `config/system/additional/Development.php`

TYPO3_CONTEXT=Development/Docker (for development inside docker boilerplate):
- `config/system/additional/Development.php`
- `config/system/additional/Development/Docker.php`

TYPO3_CONTEXT=Production/Preview (for preview):
- `config/system/additional/Development.php`
- `config/system/additional/Development/Preview.php`

TYPO3_CONTEXT=Production/Live/Server4711 (specific live server configuration):
- `config/system/additional/Development.php`
- `config/system/additional/Development/Live.php`
- `config/system/additional/Development/Live/Server4711.php`

## Environment variables

cron_context will read the TYPO3 DB credentials from the following environment variables if present:

* MYSQL_DB
* MYSQL_HOST
* MYSQL_PORT
* MYSQL_USER
* MYSQL_PASS

## Advanced usage

If you don't want to use `EXT:cron_context/Configuration/` you can customize your own loading in `config/system/additional.php`

```php
<?php
defined('TYPO3') || die('Access denied.');

// Prevent double loading
if (defined('CRON_TYPO3_ADDITIONALCONFIGURATION')) {
    return;
}

require_once __DIR__ . '/ContextLoader.php';

$confLoader = new \Cron\CronContext\ContextLoader();
$confLoader
        // Add project context configuration
    ->addContextConfiguration(\TYPO3\CMS\Core\Core\Environment::getConfigPath() . '/system/additional')
        // Add local configuration
    ->addConfiguration(\TYPO3\CMS\Core\Core\Environment::getConfigPath() . '/system/additional/local.php')
        // Load configuration files (maybe cached)
    ->loadConfiguration()
        // Add context name to sitename (if in development context)
    ->appendContextNameToSitename();
unset($confLoader);
```
