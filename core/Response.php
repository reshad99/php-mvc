<?php

namespace app\core;

class Response
{
    public function setStatusCode(int $code)
    {
        http_response_code($code);
    }

    public function redirectBack()
    {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    public function redirect($location)
    {
        header("Location: $location");
    }
}
