<?php

use LightCms\Console\App;
use Inhere\Library\DI\ContainerManager;

require BASE_PATH . '/vendor/autoload.php';

$di = Sws::$di = ContainerManager::make();

$app = new App($di);
