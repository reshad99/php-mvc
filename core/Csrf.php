<?php

namespace app\core;

class Csrf
{
    public static function generateToken()
    {
        $token = bin2hex(random_bytes(32));
        Session::flash('csrf_token', $token);
        return $token;
    }

    public static function validateToken($token)
    {
        return !empty(Session::get('csrf_token')) && $token === Session::get('csrf_token');
    }
}
