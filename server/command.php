<?php

require_once __DIR__ . '/vendor/yiisoft/yii/framework/yiilite.php';
$configFile = __DIR__ . '/protected/config/command.php';

/* Create web application and run
-------------------------------------------------- */

Yii::createConsoleApplication($configFile)->run();
