<?php

use Inhere\Library\DI\Container;
use Inhere\Library\Collections\Configuration;
use Micro\Web\App;

defined('IN_SWOOLE') || define('IN_SWOOLE', false);

/** @var Container $di */
$di = Mgr::$di = new Container();

// register some service components
$di->set('config', function () {
    return Configuration::makeByEnv(
        BASE_PATH . '/.env', // locFile
        dirname(__DIR__)  . '/config/web.php', // baseFile
        dirname(__DIR__)  . '/config/web/{env}.php', // envFile
        true
    );
});

// load services from config
$di->sets($di['config']->remove('services'));

$app = new App($di);

// load routes
require dirname(__DIR__) . '/app/Http/routes.php';
