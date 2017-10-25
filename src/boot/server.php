#!/usr/bin/env php
<?php
/**
 * @var AppServer $server
 * @usage `php bin/server start|stop|...`
 */

use LightCms\Web\App;
use LightCms\Web\AppServer;

$server = new AppServer(require dirname(__DIR__) . '/config/server.php');

$server->on(AppServer::ON_BOOTSTRAP, function (AppServer $svr) {
  /** @var APP $app */
  require __DIR__ . '/web.php';

  $svr->setApp($app);
});

$server->on(AppServer::ON_SERVER_CREATE, function () {
    // prepare load classes
//  $req = HttpFactory::createServerRequest('GET', Uri::createFromString('/'));
//  $res = HttpFactory::createResponse();
});

// 启动worker 后
$server->on(AppServer::ON_WORKER_STARTED, function (AppServer $server) {

});
