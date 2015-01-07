<?php

// database settings

$config['components']['db'] = array(
	'connectionString' => 'mysql:host=127.0.0.1;dbname=fitbody',
	'emulatePrepare' => true,
	'username' => 'root',
	'password' => '',
	'charset' => 'utf8',
);

$config['components']['forumDb'] = array(
    'connectionString' => 'mysql:host=127.0.0.1;dbname=fitbody',
    'emulatePrepare' => true,
    'username' => 'root',
    'class'=>'CDbConnection',
    'password' => '',
    'charset' => 'utf8',
    'tablePrefix' => 'phpbb_',
);

if (!defined('YII_DEBUG') || !YII_DEBUG) {
    $config['components']['db']['schemaCachingDuration'] = 3600;
}
if (defined('YII_DEBUG') && YII_DEBUG) {
    $config['components']['db']['enableProfiling'] = true;
    $config['components']['db']['enableParamLogging'] = true;
}
