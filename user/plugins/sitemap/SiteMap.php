<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-10-18
 * Time: 18:58
 */

namespace Micro\Plugins\SiteMap;

use Micro\Web\AloneAction;

/**
 * Class SiteMap
 * @package Micro\Components
 */
class SiteMap extends AloneAction
{
    public function run()
    {
        if (!app()->checkRights($this->id)) {
            $zbp->showError(6);
        }

        if (!app()->checkPlugin('sitemap')) {
            $zbp->showError(48);
        }

        if (count($_POST) > 0) {
            $this->genSiteMap();
        }
    }

    protected function genSiteMap()
    {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><urlset />');
        $xml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $url = $xml->addChild('url');
        $url->addChild('loc', $zbp->host);
        if (GetVars('category')) {
            foreach ($zbp->categorys as $c) {
                $url = $xml->addChild('url');
                $url->addChild('loc', $c->Url);
            }
        }
        if (GetVars('article')) {
            $array = $zbp->GetArticleList(
                null,
                array(array('=', 'log_Status', 0)),
                null,
                null,
                null,
                false
            );
            foreach ($array as $key => $value) {
                $url = $xml->addChild('url');
                $url->addChild('loc', $value->Url);
            }
        }
        if (GetVars('page')) {
            $array = $zbp->GetPageList(
                null,
                array(array('=', 'log_Status', 0)),
                null,
                null,
                null
            );
            foreach ($array as $key => $value) {
                $url = $xml->addChild('url');
                $url->addChild('loc', $value->Url);
            }
        }
        if (GetVars('tag')) {
            $array = $zbp->GetTagList();
            foreach ($array as $key => $value) {
                $url = $xml->addChild('url');
                $url->addChild('loc', $value->Url);
            }
        }
        file_put_contents($zbp->path . 'sitemap.xml', $xml->asXML());
        $zbp->SetHint('good');
        Redirect($_SERVER["HTTP_REFERER"]);
    }
}
