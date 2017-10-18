<?php
/**
 * config for web
 */

use Inhere\Library\Helpers\Arr;
use Inhere\Library\Components\ViewRenderer;
use Inhere\Route\ORouter;
use Inhere\Route\Dispatcher;
use Overtrue\Pinyin\MemoryFileDictLoader;
use Overtrue\Pinyin\Pinyin;

return Arr::merge(require __DIR__ . '/_common.php', [
    'determineRouteBeforeAppMiddleware' => false,

    'response' => [
        'chunkSize' => 1024,
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
            'target' => Dispatcher::class,
            'config' => [
                'filterFavicon' => true,
                'dynamicAction' => true,
                Dispatcher::ON_NOT_FOUND => '/404'
            ],
            'matcher' => function ($path, $method) {
                /** @var ORouter $router */
                $router = \Sws::$app->get('httpRouter');
                return $router->match($path, $method);
            },
        ],
        'renderer' => [
            'target' => ViewRenderer::class,
            'viewsPath' => dirname(__DIR__) . '/resources/views',
            '_options' => ['active' => 1, 'aliases' => 'viewRenderer'],
        ],
    ]
]);
