<?php

use Inhere\Library\Collections\Configuration;
use Inhere\Library\DI\Container;
use Micro\Console\App;

/** @var Container $di */
$di = Mgr::$di = new Container();

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
