<?php

use Inhere\Library\Components\Language;

function app($id = null)
{
    if ($id) {
        return \Sys::$di->get($id);
    }

    return \Sys::$app;
}

function di($name = null)
{
    if ($name) {
        return \Sys::$di->get($name);
    }

    return \Sys::$di;
}

function tl($key, array $args = [], $lang = null)
{
    /** @see Language::translate() */
    return \Sys::$di->get('lang')->translate($key, $args, $lang);
}

function app_path($path)
{
    return \Sys::alias($path);
}

function app_plugin()
{
}

function cms_data()
{
    return \Sys::$di->get('dataProxy');
}
