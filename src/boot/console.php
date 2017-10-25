<?php

use Inhere\Library\Collections\Configuration;
use Inhere\Library\DI\ContainerManager;
use LightCms\Console\App;

/** @var Inhere\Library\DI\Container $di */
$di = Mgr::$di = ContainerManager::make();

// register some service components
$di->set('config', function () {
    return Configuration::makeByEnv(
        BASE_PATH . '/.env', // locFile
        dirname(__DIR__)  . '/config/console.php', // baseFile
        dirname(__DIR__)  . '/config/console/{env}.php' // envFile
    );
});

$app = new App($di);

require dirname(__DIR__) . '/app/Console/routes.php';

$app->run();
