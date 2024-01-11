<?php

namespace app\core\Exceptions;

class NotFoundException extends BaseException
{
    public function __construct(array $errors = [], $code = 404)
    {
        parent::__construct("Page not found", $code, $errors);
    }
}
