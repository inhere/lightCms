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