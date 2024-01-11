<?php

namespace app\core;

session_start();


class Session
{
    public static function flash($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function check($key)
    {
        return $_SESSION[$key] ?? null;
    }

    public static function get($key)
    {
        $value = $_SESSION[$key] ?? null;
        unset($_SESSION[$key]);
        return $value;
    }
}
