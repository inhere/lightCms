<?php
/**
 * @var Inhere\Route\ORouter $router
 */

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ErrorController;

$router->get('/', HomeController::class . '@index');

$router->any('/404', ErrorController::class . '@notFound');
$router->any('/405', ErrorController::class . '@notAllowed');
$router->any('/500', ErrorController::class . '@error');
