<?php

use Src\Cache;

function user()
{
    if ($_SESSION['user_id']) {
        return Cache::get("user_{$_SESSION['user_id']}");
    }

    return null;
}

function systemInfo()
{
    return 'system';
}

function baseUrl()
{
    $config = require __DIR__ . '/../../config/app.php';

    return $config['base_url'];
}

function url($path)
{
    $url = baseUrl() . '/' . $path;

    return str_replace('//', '/', $url);
}

function asset($path)
{
    $url = baseUrl() . '/';

    $url = str_replace('//', '/', $url) . '/public';

    return $url . $path;
}

function dbConfig($key)
{
    $config = require __DIR__ . '/../../config/db.php';

    return $config[$key];
}


function arrayToObject(array $array): stdClass
{
    $object = new stdClass();

    foreach ($array as $key => $value) {
        $key = is_numeric($key) ? (string) $key : $key;

        if (is_array($value)) {
            $object->$key = arrayToObject($value);
        } else {
            $object->$key = $value;
        }
    }

    return $object;
}

function redirect($path)
{
    header('Location: ' . url($path));
}
