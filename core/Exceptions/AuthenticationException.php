<?php

namespace app\core\Exceptions;

class AuthenticationException extends BaseException
{
    public function __construct(string $message, $code = 401)
    {
        parent::__construct($message, $code);
    }
}
