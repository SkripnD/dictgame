<?php
    
/**
 * If there is no code outside webroot, set: __DIR__
 * and extract the web folder to the root of the site
 */

define('WEBROOT', __DIR__ . '/..');

/* Initialization environment
-------------------------------------------------- */

defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
    
if (APPLICATION_ENV == 'production') {
    require_once WEBROOT . '/vendor/yiisoft/yii/framework/yiilite.php';
    
} else {
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    
    require_once WEBROOT . '/vendor/yiisoft/yii/framework/yii.php';
}

$configFile = WEBROOT . '/protected/config/app.php';

/* Create web application and run
-------------------------------------------------- */

require dirname(__FILE__).'/../vendor/autoload.php';

$app = Yii::createWebApplication($configFile);

require_once WEBROOT . '/protected/helpers/Global.php';

$app->run();
