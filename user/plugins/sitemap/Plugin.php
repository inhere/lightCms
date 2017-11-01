<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-10-18
 * Time: 18:58
 */

namespace Micro\Plugins\SiteMap;

use Micro\Base\AbstractPlugin;
use Micro\Web\AloneAction;

/**
 * Class Plugin
 * @package Micro\Components
 */
class Plugin extends AbstractPlugin
{
    /**
     * @see Piwik\Plugin::registerEvents
     */
    public function registerEvents(): array
    {
        return array(
            'AssetManager.getStylesheetFiles' => 'getStylesheetFiles',
            'AssetManager.getJavaScriptFiles' => 'getJsFiles',
            'Translate.getClientSideTranslationKeys' => 'getClientSideTranslationKeys',
        );
    }

    public function load()
    {
        // TODO: Implement load() method.
    }

    public function run()
    {
        // TODO: Implement run() method.
    }

}
