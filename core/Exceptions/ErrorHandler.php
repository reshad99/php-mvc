<?php

namespace app\core\Exceptions;

use app\core\Session;
use Exception;

class ErrorHandler
{
    public static function handleException(\Throwable $e)
    {
        if ($e instanceof NotFoundException) {
            return self::handleNotFoundException($e);
        } elseif ($e instanceof ValidationException) {
            return self::handleValidationException($e);
        } else {
            return self::handleGenericException($e);
        }
    }

    protected static function handleNotFoundException(NotFoundException $e)
    {
        http_response_code(404);
        if (app()->request->expectsJson()) {
            return json_encode(['error' => 'Not Found']);
        } else {
            return app()->router->renderView("errors._404");
        }
    }

    protected static function handleValidationException(ValidationException $e)
    {
        http_response_code(422);
        if (app()->request->expectsJson()) {
            return json_encode(['error' => 'Validation Error', 'details' => $e->getErrors()]);
        } else {
            Session::flash('errors', $e->getErrors());
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }

    protected static function handleGenericException(\Throwable $e)
    {
        http_response_code(500);
        if (app()->request->expectsJson()) {
            return json_encode(['error' => 'Internal Server Error']);
        } else {
            $error = $e->getMessage();
            return app()->router->renderView("errors.500", compact('error'));
        }
    }
}
