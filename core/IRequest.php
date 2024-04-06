<?php

namespace app\core;

use app\core\Exceptions\ValidationException;

interface IRequest
{


    public function getPath();

    public function getMethod(): string;


    public function isAjax(): bool;


    public function expectsJson(): bool;


    public function getBody();


    public function __get($name);


    public function validate($formData);
}
