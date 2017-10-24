<?php

use LightCms\Web\App;

define('BASE_PATH', dirname(__DIR__));

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/src/boot/web.php';

/** @var App $app */
$app->run();
