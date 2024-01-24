<?php

namespace app\core\Exceptions;

use app\core\Session;
use Exception;

class ErrorHandler
{
    public static function handleException(\Throwable $e, $cli = false)
    {
        if ($e instanceof NotFoundException || $e instanceof ModelNotFoundException) {
            return self::handleNotFoundException($e);
        } elseif ($e instanceof ValidationException) {
            return self::handleValidationException($e);
        } else {
            return self::handleGenericException($e, $cli);
        }
    }

    protected static function handleNotFoundException(NotFoundException $e)
    {
        http_response_code(404);
        if (app()->request->expectsJson()) {
            return json_encode(['error' => $e->getMessage()]);
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
            return response()->redirectBack();
        }
    }

    protected static function handleGenericException(\Throwable $e, bool $cli)
    {
        http_response_code(500);
        if (app()->request->expectsJson() || $cli === true) {
            return json_encode(['error' => $e->getMessage()]);
        } else {
            $error = $e->getMessage();
            return app()->router->renderView("errors.500", compact('error'));
        }
    }
}
