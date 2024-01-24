<?php

use app\core\Application;
use app\core\Auth;
use app\core\Response;
use app\core\Session;

function app(): Application
{
    return Application::$app;
}

function getError($field, $errors)
{
    return $errors[$field][0] ?? '';
}

function config(string $key)
{
    $parts = explode('.', $key);
    $file = $parts[0];
    $config = require __DIR__ . '/../config/' . $file . '.php';
    return $config[$parts[1]] ?? null;
}

function env(string $key)
{
    return $_ENV[$key];
}

function pdo(): PDO
{
    return Application::$app->database->pdo;
}

function session(): Session
{
    return new Session;
}

function response(): Response
{
    return app()->response;
}

function auth(): Auth
{
    return app()->auth;
}
