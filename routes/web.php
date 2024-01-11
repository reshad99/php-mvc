<?php

use app\controllers\AuthController;
use app\controllers\SiteController;
use app\core\Router;
use app\requests\ContactRequest;
use app\requests\RegisterRequest;

Router::get('/', [SiteController::class, 'index']);
Router::get('/contact', [SiteController::class, 'indexContact']);
Router::post('/contact', [SiteController::class, 'contactPost', ContactRequest::class]);
Router::get('/login', [AuthController::class, 'login']);
Router::get('/register', [AuthController::class, 'register']);
Router::post('/register', [AuthController::class, 'tryRegister', RegisterRequest::class]);
