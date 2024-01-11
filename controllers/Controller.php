<?php

namespace app\controllers;

use app\core\Application;

class Controller
{
    public function view($path, $params = [], $layout = 'main')
    {
        return app()->router->renderView($path, $params, $layout);
    }
}
