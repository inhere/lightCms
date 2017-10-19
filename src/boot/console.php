<?php

use LightCms\Console\App;
use Inhere\Library\DI\ContainerManager;

require BASE_PATH . '/vendor/autoload.php';

/** @var Inhere\Library\DI\Container $di */
$di = Sys::$di = ContainerManager::make();

$app = new App($di);

require dirname(__DIR__) . '/app/Console/routes.php';

$app->run();