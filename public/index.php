<?php

require_once __DIR__ . "/../vendor/autoload.php";

use app\core\Application;
use app\core\Exceptions\ErrorHandler;

$app = new Application(dirname(__DIR__));

require_once __DIR__ . "/../routes/web.php";

try {
    $app->run();
} catch (\Throwable $e) { // \Throwable PHP 7.0 ve üzeri için genel istisna yakalama
    echo ErrorHandler::handleException($e);
}