<?php

use app\core\Application;
use app\core\Session;

function app(): Application
{
    return Application::$app;
}

function getError($field, $errors)
{
    return $errors[$field][0] ?? '';
}
