<?php

namespace app\middleware;

abstract class BaseMiddleware
{
    abstract public function run(): bool;
}
