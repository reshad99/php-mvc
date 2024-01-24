<?php

namespace app\core\Exceptions;

class ModelNotFoundException extends BaseException
{
    public function __construct(string $message, $code = 404)
    {
        parent::__construct($message, $code);
    }
}
