<?php
/**
 * the common config
 */

use Inhere\Library\Components\Language;
// use Overtrue\Pinyin\MemoryFileDictLoader;
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
    ],
];
