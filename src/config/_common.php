<?php
/**
 * the common config
 */

use Inhere\Library\Components\Language;
// use Overtrue\Pinyin\MemoryFileDictLoader;
use Inhere\Library\Utils\LiteLogger;
use Overtrue\Pinyin\Pinyin;

return [
    'debug' => false,
    'env'   => 'pdt',
    'charset' => 'UTF-8',
    'timeZone' => 'Asia/Shanghai',
    'rootPath' => BASE_PATH,

    'enableCsrfToken' => true,

    'services' => [
        /**
         * basic service
         */

        'logger' => [
            'target' => LiteLogger::class,
            'name' => 'app',
            'logFile' => '@user/tmp/logs/application.log',
            'basePath' => '@user/tmp/logs',
            'level' => LiteLogger::DEBUG,
            'splitType' => 1,
            'bufferSize' => 1000, // 1000,
            'pathResolver' => function ($path) {
                return \Mgr::alias($path);
            }
        ],
        'lang' => [
            'target' => Language::class,
            'lang' => 'zh-CN',
            'langs' => ['en', 'zh-CN'],
            'basePath' => dirname(__DIR__) . '/resources/langs',
        ],
        'pinyin' => [
            'target' => Pinyin::class,
            // '_args' => [ MemoryFileDictLoader::class ],
        ],
        'db' => [
            'target' => Inhere\Library\Components\DatabaseClient::class,
            '_args' => [
                [
                    'debug' => 1,
                    'host' => 'localhost',
                    'port' => '3306',
                    'user' => 'root',
                    'password' => 'root',
                    'database' => 'zblog',
                    'tablePrefix' => 'zbp_',
                ]
            ]
        ]
    ],
];
