<?php

$profiling = false;
$blocking = false;
$allowIps = array('127.0.0.1');
$message = 'Извините, идут работы, мы скоро вернемся…';
/* Database
-------------------------------------------------- */

$configs['components']['db'] = array(
    'class' => 'CDbConnection',
    'connectionString' => 'mysql:host=127.0.0.1;dbname=soyuz',
    'username' => 'root',
    'password' => '',
    //'connectionString' => 'mysql:host=soyuzdev.cetis.ru;dbname=soyuz_soyuz',
    //'username' => 'soyuz_soyuz',
    //'password' => 'NaviBfKzafFN9pkPcTr',
    'emulatePrepare' => true,
    'charset' => 'UTF8',
    'schemaCachingDuration' => YII_DEBUG ? 0 : 0,
);

/* Cache
-------------------------------------------------- */
/*$configs['components']['cache'] = array(
	'class' => 'system.caching.CMemCache',
	'useMemcached' => false
);
*/
/* Sentry — https://getsentry.com or your own Sentry server
https://github.com/getsentry/sentry
-------------------------------------------------- */
// $configs['components']['sentry'] = array(
// 'class' => 'ext.yii-sentry.components.RSentryClient',
// 'dsn' => '',
// );

// $configs['components']['log'] = array(
// 'class' => 'CLogRouter',
// 'routes' => array(
// array(
// 'class' => 'ext.yii-sentry.components.RSentryLogRoute',
// 'except' => 'exception.CHttpException.404',
// 'levels' => 'warning, error, info',
// ),
// ),
// );

/* GII
-------------------------------------------------- */

if (YII_DEBUG) {
    $configs['modules']['gii'] = array(
        'class' => 'system.gii.GiiModule',
        'password' => 'root',
        'ipFilters' => $allowIps,
    );
}
$configs['modules']['gii'] = array(
    'class' => 'system.gii.GiiModule',
    'password' => 'root',
    'ipFilters' => $allowIps,
);

/* Profiling
-------------------------------------------------- */
if (false) {
    $configs['preload'] = array('debug');
    $configs['components']['db']['enableProfiling'] = true;
    $configs['components']['db']['enableParamLogging'] = true;
    $configs['components']['debug'] = array(
        'class' => 'ext.yii2-debug.Yii2Debug',
        'allowedIPs' => $allowIps
    );
}

/* Blocking site
-------------------------------------------------- */

if ($blocking || file_exists($configs['basePath'].'/runtime/maintain')) {
    $configs['catchAllRequest'] = array(
        'message' => $message,
        isset($_SERVER['REMOTE_ADDR']) && in_array($_SERVER['REMOTE_ADDR'], $allowIps)
            ? null : 'index/maintain',
    );
}

return $configs;