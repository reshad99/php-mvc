<?php

namespace app\core;

use app\core\Exceptions\ValidationException;
use Exception;

class Request implements IRequest
{

    public function rules()
    {
    }

    public function verifyCsrfToken()
    {
        if ($this->getMethod() === 'post'  && !$this->expectsJson()) {
            $postedToken = $this->getBody()['csrf_token'] ?? '';
            if (!Csrf::validateToken($postedToken)) {
                throw new \Exception("Invalid CSRF token");
            }
        }
    }

    public function generateToken()
    {
        if ($this->getMethod() !== 'post') {
            Csrf::generateToken();
        }
    }

    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if ($position === false) {
            return $path;
        }
        return substr($path, 0, $position);
    }

    public function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function isAjax(): bool
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        }
        return false;
    }

    public function expectsJson(): bool
    {
        if (!empty($_SERVER['HTTP_ACCEPT']) && strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/json') !== false) {
            return true;
        }
        return false;
    }

    public function getBody()
    {
        $body = [];

        if ($this->getMethod() === 'get') {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        if ($this->getMethod() === 'post') {
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        return $body;
    }

    public function __get($name)
    {
        return $this->getBody()[$name] ?? null;
    }

    public function validate($formData)
    {
        $validator = new Validator($formData);
        $validator->validate($this->rules());

        if (!$validator->passes()) {
            throw new ValidationException($validator->getErrors());
        }
    }
}
