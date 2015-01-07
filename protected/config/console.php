<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.a
$config = array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Console Application',

	'preload'=>array('log'),

	'import'=>array(
		'application.commands.BaseCommand'
	),

	// application components
	'components'=>array(
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
	),
	'commandMap'=>array(
		'migrate'=>array(
			'class'=>'system.cli.commands.MigrateCommand',
			'migrationPath'=>'application.migrations',
			'migrationTable'=>'migrations',
			'connectionID'=>'db',
		),
    ),
);
require_once 'main.local.php';
return $config;