<?php

namespace Cron\CronContext;

use TYPO3\CMS\Core\Core\Environment;

/**
 * Additional configuration loader (based on context)
 *
 * Examples:
 *
 * TYPO3_CONTEXT=Production
 *    -> typo3conf/AdditionalConfiguration/Production.php
 *
 * TYPO3_CONTEXT=Testing
 *    -> typo3conf/AdditionalConfiguration/Testing.php
 *
 * TYPO3_CONTEXT=Development
 *    -> typo3conf/AdditionalConfiguration/Development.php
 *
 * TYPO3_CONTEXT=Production/Staging
 *    -> typo3conf/AdditionalConfiguration/Production.php
 *    -> typo3conf/AdditionalConfiguration/Production/Staging.php
 *
 * TYPO3_CONTEXT=Production/Live
 *    -> typo3conf/AdditionalConfiguration/Production.php
 *    -> typo3conf/AdditionalConfiguration/Production/Live.php
 *
 * TYPO3_CONTEXT=Production/Live/Server4711
 *    -> typo3conf/AdditionalConfiguration/Production.php
 *    -> typo3conf/AdditionalConfiguration/Production/Live.php
 *    -> typo3conf/AdditionalConfiguration/Production/Live/Server4711.php
 *
 */
class ContextLoader
{

    /**
     * Application context
     *
     * @var \TYPO3\CMS\Core\Core\ApplicationContext
     */
    protected $applicationContext;

    /**
     * Context list (reversed)
     *
     * @var array
     */
    protected $contextList = [];

    /**
     * Configuration path list (simple files)
     *
     * @var array
     */
    protected $confPathList = [];

    /**
     * Cache file (only set if cache is used)
     *
     * @var null|string
     */
    protected $cacheFile;

    /**
     * Construct
     */
    public function __construct()
    {
        define('CRON_TYPO3_ADDITIONALCONFIGURATION', 1);

        $this
            ->init()
            ->checkEnvironment()
            ->buildContextList();
    }

    /**
     * Init
     *
     * @return $this
     */
    public function init()
    {
        $this->applicationContext = Environment::getContext();

        return $this;
    }

    /**
     * Check environment
     *
     * @return $this
     */
    public function checkEnvironment()
    {
        // Check CLI mode
        if (defined('TYPO3_cliMode')) {
            $contextEnv = getenv('TYPO3_CONTEXT');

            if (empty($contextEnv)) {
                echo '[ERROR] TYPO3_CONTEXT not set or found for AdditionalConfiguration.php' . "\n";
                exit(1);
            }
        }

        return $this;
    }

    /**
     * Use cache
     *
     * return $this;
     */
    public function useCache()
    {
        // TODO: maybe the caching is not safe for race conditions
        $this->cacheFile = Environment::getVarPath() . '/cache/code/cron_context_conf.php';

        return $this;
    }

    /**
     * Use cache if TYPO3_CONTEXT is production
     *
     * @return $this
     */
    public function useCacheInProduction()
    {
        if ($this->applicationContext->isProduction()) {
            $this->useCache();
        }

        return $this;
    }

    /**
     * Add path for automatic context loader
     *
     * @param string $path Path to file
     *
     * @return $this
     */
    public function addContextConfiguration($path)
    {
        $this->confPathList['context'][] = $path;

        return $this;
    }

    /**
     * Add configuration to loader
     *
     * @param string $path Path to file
     *
     * @return $this
     */
    public function addConfiguration($path)
    {
        $this->confPathList['file'][] = $path;

        return $this;
    }

    /**
     * Load configuration
     *
     * @return $this
     */
    public function loadConfiguration()
    {
        if (!$this->loadCache()) {
            $this
                ->loadContextConfiguration()
                ->loadFileConfiguration()
                ->buildCache();
        }

        return $this;
    }

    /**
     * Build context list
     *
     * @return $this
     */
    protected function buildContextList()
    {
        $contextList    = [];
        $currentContext = $this->applicationContext;
        do {
            $contextList[] = (string)$currentContext;
        } while ($currentContext = $currentContext->getParent());

        // Reverse list, general first (eg. PRODUCTION), then specific last (eg. SERVER)
        $this->contextList = array_reverse($contextList);

        return $this;
    }

    /**
     * Load from cache
     */
    protected function loadCache()
    {
        $ret = false;

        if ($this->cacheFile && file_exists($this->cacheFile)) {
            $conf = unserialize(file_get_contents($this->cacheFile));

            if (!empty($conf)) {
                $GLOBALS['TYPO3_CONF_VARS'] = $conf;
                $ret                        = true;
            }
        }

        return $ret;
    }

    /**
     * Build context config cache
     *
     * @return $this
     */
    protected function buildCache()
    {
        if ($this->cacheFile) {
            file_put_contents($this->cacheFile, serialize($GLOBALS['TYPO3_CONF_VARS']));
        }

        return $this;
    }

    /**
     * Load configuration based on current context
     *
     * @return $this
     */
    protected function loadContextConfiguration()
    {
        if (!empty($this->confPathList['context'])) {
            foreach ($this->confPathList['context'] as $path) {
                foreach ($this->contextList as $context) {
                    // Sanitize context name
                    $context = preg_replace('/[^-_\.a-zA-Z0-9\/]/', '', $context);

                    // Build config file name
                    $this->loadConfigurationFile($path . '/' . $context . '.php');
                }
            }
        }

        return $this;
    }

    /**
     * Load simple file configuration
     *
     * @return $this
     */
    protected function loadFileConfiguration()
    {
        if (!empty($this->confPathList['file'])) {
            foreach ($this->confPathList['file'] as $path) {
                $this->loadConfigurationFile($path);
            }
        }

        return $this;
    }

    /**
     * Load configuration file
     *
     * @param string $configurationFile Configuration file
     *
     * @return $this
     */
    protected function loadConfigurationFile($configurationFile)
    {
        // Load config file
        if (file_exists($configurationFile)) {
            // Keep this variable for automatic injection into requried files!
            $contextLoader = $this;

            // Load configuration file
            $retConf = require $configurationFile;

            // Apply return'ed configuration (if available)
            if (!empty($retConf) && is_array($retConf)) {
                $GLOBALS['TYPO3_CONF_VARS'] = array_replace_recursive($GLOBALS['TYPO3_CONF_VARS'], $retConf);
            }
        }

        return $this;
    }

    /**
     * Append context name to sitename (if not production)
     *
     * @return $this
     */
    public function appendContextNameToSitename()
    {
        if (!$this->applicationContext->isProduction()) {
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'] = sprintf(
                '%s [[%s]]',
                $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename'],
                strtoupper((string)$this->applicationContext
            ));
        }

        return $this;
    }

    /**
     * Set extension configuration value
     *
     * @param string $extension Extension name
     * @param string $setting   Configuration setting name
     * @param mixed  $value     Configuration value
     *
     * @return $this
     */
    public function setExtensionConfiguration($extension, $setting, $value = null)
    {
        throw new \Exception('Please do not use this function anymore! It\'s not compatible to the way the extConf is handled in TYPO3 >= 9. Use $GLOBALS[\'TYPO3_CONF_VARS\'][\'EXTENSIONS\'][\''.$extension.'\'][\''.$setting.'\'] = \'' . $value . '\'; instead.');
    }

    /**
     * Set extension configuration value (by list)
     *
     * @param string $extension   Extension name
     * @param array  $settingList List of settings
     *
     * @return $this
     */
    public function setExtensionConfigurationList($extension, array $settingList)
    {
        $this->setExtensionConfiguration($extension, key($settingList), current($settingList));
    }
}
