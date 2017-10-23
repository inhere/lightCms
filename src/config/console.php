<?php
/**
 * config for console
 */

use Inhere\Library\Helpers\Arr;
use Inhere\Library\Utils\LiteLogger;

return Arr::merge(require __DIR__ . '/_common.php', [
    'assetUrls' => [
        'jquery' => 'https://cdn.bootcss.com/jquery/3.2.1/jquery.slim.min.js',
        'riot3' => 'https://cdn.bootcss.com/riot/3.7.3/riot+compiler.min.js',
        'bootstrap4' => [
            'https://cdn.bootcss.com/bootstrap/4.0.0-beta/css/bootstrap.min.css',
            'https://cdn.bootcss.com/bootstrap/4.0.0-beta/js/bootstrap.min.js',
        ]
    ],
    'services' => [
        'logger' => [
            'name' => 'console',
            'logFile' => '@user/tmp/logs/console.log',
            'level' => LiteLogger::DEBUG,
            'splitType' => 1,
            'bufferSize' => 1000, // 1000,
        ],
    ],
]);
