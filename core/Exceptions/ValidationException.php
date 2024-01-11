<?php

namespace app\core\Exceptions;

class ValidationException extends BaseException
{
    public function __construct(array $errors, $code = 422)
    {
        parent::__construct("Validation errors occurred", $code, $errors);
    }

    public function getErrors()
    {
        return $this->getData();
    }
}
