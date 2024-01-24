<?php

namespace app\middleware;

class AuthMiddleware extends BaseMiddleware
{
    public function run(): bool
    {
        if (auth()->check()) {
            return true;
        }

        return false;
    }
}
