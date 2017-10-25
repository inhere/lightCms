<?php

use Inhere\Library\Components\Language;

function app($id = null)
{
    if ($id) {
        return \Mgr::$di->get($id);
    }

    return \Mgr::$app;
}

function di($name = null)
{
    if ($name) {
        return \Mgr::$di->get($name);
    }

    return \Mgr::$di;
}

function tl($key, array $args = [], $lang = null)
{
    /** @see Language::translate() */
    return \Mgr::$di->get('lang')->translate($key, $args, $lang);
}

function app_path($path)
{
    return \Mgr::alias($path);
}

function app_plugin()
{
}

function cms_data()
{
    return \Mgr::$di->get('dataProxy');
}
