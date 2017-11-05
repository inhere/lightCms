<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-03-17
 * Time: 11:20
 * @var \Micro\Console\App $app
 */

//use App\Console\Commands\PackCommand;

$app->commands([
    'demo' => function () {
        echo 'test';
    },
    App\Console\Commands\TestCommand::class,
//    'demo' => \App\console\commands\DemoCommand::class,
    //'book:build' => \App\console\commands\BuildCommand::class,
//    PackCommand::class,
]);

$app->controllers([
    \App\Console\Controllers\AssetController::class,
    \App\Console\Controllers\DevController::class,
    \App\Console\Controllers\CmsController::class,
    \App\Console\Controllers\PluginController::class,
    \App\Console\Controllers\ServerController::class,
]);
