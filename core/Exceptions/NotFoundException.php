<?php

namespace app\core\Exceptions;

class NotFoundException extends BaseException
{
    public function __construct(string $message = "Page not found", $code = 404)
    {
        parent::__construct($message, $code);
    }
}
