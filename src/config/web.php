<?php
/**
 * config for web
 */

use Inhere\Library\Helpers\Arr;
use Inhere\Library\Web\ViewRenderer;
use Inhere\Route\ORouter;
use Micro\Web\RouteDispatcher;

return Arr::merge(require __DIR__ . '/_common.php', [

    'displayErrorDetails' => false,
    'determineRouteBeforeAppMiddleware' => false,

    'filterFavicon' => true,

    'response' => [
        'chunkSize' => 4096,
        'httpVersion' => '1.1',
        'addContentLengthHeader' => true,
    ],

    'services' => [
        /**
         * http service
         */

        'router' => [
            'target' => ORouter::class,
            'config' => [
                'ignoreLastSep' => true,
                'tmpCacheNumber' => 200,
            ],
        ],
        'routeDispatcher' => [
            'target' => RouteDispatcher::class,
            'outputBuffering' => 'append',
            'config' => [
                'dynamicAction' => true,
            ],
        ],
        'renderer' => [
            'target' => ViewRenderer::class,
            'viewsPath' => dirname(__DIR__) . '/resources/views',
        ],
    ]
]);
