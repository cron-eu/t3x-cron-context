<?php

namespace Cron\CronContext;

use TYPO3\CMS\Core\Core\Environment;

/**
 * Additional configuration loader (based on context)
 *
 * Examples:
 *
 * TYPO3_CONTEXT=Production
 *    -> config/system/additional/Production.php
 *
 * TYPO3_CONTEXT=Testing
 *    -> config/system/additional/Testing.php
 *
 * TYPO3_CONTEXT=Development
 *    -> config/system/additional/Development.php
 *
 * TYPO3_CONTEXT=Production/Staging
 *    -> config/system/additional/Production.php
 *    -> config/system/additional/Production/Staging.php
 *
 * TYPO3_CONTEXT=Production/Live
 *    -> config/system/additional/Production.php
 *    -> config/system/additional/Production/Live.php
 *
 * TYPO3_CONTEXT=Production/Live/Server4711
 *    -> config/system/additional/Production.php
 *    -> config/system/additional/Production/Live.php
 *    -> config/system/additional/Production/Live/Server4711.php
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
        if (!Environment::isCli() &&
            empty(getenv('TYPO3_CONTEXT'))
        ) {
            echo '[ERROR] TYPO3_CONTEXT not set or found for additional.php' . "\n";
            exit(1);
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
        $this
            ->loadContextConfiguration()
            ->loadFileConfiguration();

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
