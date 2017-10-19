<?php

function app($id = null)
{
    if ($name) {
        return \Sys::$di->get($name);
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

function tl($key)
{
    return \Sys::alias($path);
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
