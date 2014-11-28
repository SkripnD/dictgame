<?php

$configs = require_once __DIR__ . '/app.php';



unset($configs['defaultController']);
unset($configs['controllerMap']);
unset($configs['catchAllRequest']);

$configs['commandMap']['migrate']['class'] = 'application.components.Migrate';

return $configs;
