<?php
/**
 * config for console
 */

use Inhere\Library\Helpers\Arr;
use Inhere\Library\Utils\LiteLogger;

return Arr::merge(require __DIR__ . '/_common.php', [
    'services' => [
        'logger' => [
            'name' => 'app',
            'logFile' => '@user/tmp/logs/console.log',
            'level' => LiteLogger::DEBUG,
            'splitType' => 1,
            'bufferSize' => 1000, // 1000,
        ],
    ],
]);
