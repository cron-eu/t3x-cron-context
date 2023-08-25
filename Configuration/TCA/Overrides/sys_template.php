<?php
defined('TYPO3') || die('Access denied.');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'cron_context',
    'Configuration/TypoScript/EnvironmentBanner',
    'Cron Context: Environment Banner'
);
