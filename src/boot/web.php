<?php

require BASE_PATH . '/vendor/autoload.php';

use Inhere\Library\DI\ContainerManager;
use Inhere\Library\Collections\Configuration;
use LightCms\Console\App;

/** @var Inhere\Library\DI\Container $di */
$di = Sys::$di = ContainerManager::make();

// register some service components
$di->set('config', function () {
    return Configuration::makeByEnv(
        dirname(__DIR__) . '/.env', // locFile
        dirname(__DIR__)  . '/config/web.php', // baseFile
        dirname(__DIR__)  . '/config/app/{env}.php' // envFile
    );
});

// load services from config
$di->sets($di['config']->remove('services'));

$app = new App($di);

$app->run();
