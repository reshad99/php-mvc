<?php

require_once __DIR__ . "/../vendor/autoload.php";

use app\core\Application;
use app\core\Exceptions\ErrorHandler;
use Dotenv\Dotenv;

$dotEnv = Dotenv::createImmutable(__DIR__ . "/..");
$dotEnv->load();

require_once __DIR__ . '/../core/config.php';
$app = new Application(dirname(__DIR__));
require_once __DIR__ . "/../routes/web.php";

try {
    $app->run();
} catch (\Throwable $e) { // \Throwable PHP 7.0 ve üzeri için genel istisna yakalama
    echo ErrorHandler::handleException($e);
}
