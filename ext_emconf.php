<?php

$EM_CONF[$_EXTKEY] = [
	'title' => 'Context configuration',
	'description' => 'TYPO3_CONTEXT configuration loader',
	'category' => 'system',
	'shy' => 0,
	'dependencies' => 'cms',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'stable',
	'internal' => 0,
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'author' => 'Markus Blaschke',
	'author_email' => 'info@cron.eu',
	'author_company' => 'cron IT GmbH',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'version' => '1.3.0',
	'constraints' => [
		'depends' => [
			'typo3' => '9.5.0-10.4.99',
		],
		'conflicts' => [
		],
		'suggests' => [
		],
	],
	'suggests' => [
	],
];
