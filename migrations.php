<?php

require_once __DIR__ . "/vendor/autoload.php";

use app\core\Application;
use app\core\Exceptions\ErrorHandler;
use Dotenv\Dotenv;


$dotEnv = Dotenv::createImmutable(__DIR__);
$dotEnv->load();

$app = new Application(__DIR__);



try {
    $app->database->applyMigrations('up');
} catch (\Throwable $e) { // \Throwable PHP 7.0 ve üzeri için genel istisna yakalama
    echo ErrorHandler::handleException($e, true);
}
