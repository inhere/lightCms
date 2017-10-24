#!/usr/bin/env php
<?php
/**
 * @var Container $di
 * @var \Inhere\Library\Collections\Configuration $config
 * @var AppServer $server
 * @var App $app
 * @usage `php bin/server start|stop|...`
 */

use Inhere\Http\ServerRequest;
use Inhere\Http\Uri;
use Inhere\Library\DI\Container;
use LightCms\Web\App;
use LightCms\Web\AppServer;

define('BASE_PATH', dirname(__DIR__));
define('IN_SWOOLE', true);

require dirname(__DIR__) . '/src/boot/server.php';

ini_set('display_errors', 'On');
error_reporting(E_ALL);

define('RUNTIME_ENV', $config->get('env'));
define('APP_DEBUG', $config->get('debug'));

// create app server.
$server = $di->get('server');

$server->on(AppServer::ON_BOOTSTRAP, function ($svr) {

});

$server->on(AppServer::ON_SERVER_CREATE, function () {
  // prepare load classes
//  $req = new Request('GET', Uri::createFromString('/'));
//  $res = HttpHelper::createResponse();
});

// 启动worker 后，再初始化应用(加载应用配置、路由 ...)
$server->on(AppServer::ON_WORKER_STARTED, function (AppServer $server) {

});

$server->run();