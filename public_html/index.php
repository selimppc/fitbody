<?php
// change the following paths if necessary
define('DOCUMENT_ROOT',dirname(__FILE__).'/../');
defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));
$yii=DOCUMENT_ROOT.'yii/framework/yii.php';
$config=DOCUMENT_ROOT.'protected/config/main.php';
// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG', true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
require_once($yii);
Yii::createWebApplication($config)->run();