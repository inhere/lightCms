<?php

function app()
{
    return \Sys::$app;
}

function di($name = null)
{
    if ($name) {
        return \Sys::$di->get($name);
    }

    return \Sys::$di;
}

function app_path($path)
{
    return \Sys::alias($path);
}

function cms_data()
{
    return \Sys::$di->get('dataProxy');
}
