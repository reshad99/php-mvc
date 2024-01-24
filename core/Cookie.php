<?php

namespace app\core;

use Exception;

class Cookie
{
    public static function set($key, $value)
    {
        $_COOKIE[$key] = $value;
    }

    public static function get($key)
    {
        return isset($_COOKIE[$key]) ? $_COOKIE[$key] : null;
    }
}
