<?php

namespace app\core;

use app\core\Exceptions\NotFoundException;
use Exception;

class Router
{
    public $request;
    public Response $response;
    protected static array $routes = [];

    public function __construct($request, Response $response)
    {
        $this->response = $response;
        $this->request = $request;
    }

    public static function get($path, $callback, array $middlewares = [])
    {
        self::$routes['get'][$path] = $callback;

        if (count($middlewares)) {
            self::$routes['get'][$path]['middlewares'] = $middlewares;
        }

        return app()->router;
    }

    public static function post($path, $callback, array $middlewares = [])
    {
        self::$routes['post'][$path] = $callback;

        if (count($middlewares)) {
            self::$routes['post'][$path]['middlewares'] = $middlewares;
        }

        return app()->router;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $this->request->generateToken();
        $this->request->verifyCsrfToken();
        $callback = self::$routes[$method][$path] ?? false;

        if ($callback === false) {
            throw new NotFoundException();
        }

        if (is_string($callback)) {
            return $this->renderview($callback);
        }

        if (is_array($callback)) {
            $request = $this->request;
            $callback[0] = new $callback[0];
            $controller = $callback[0];
            $method = $callback[1];


            if (isset($callback['middlewares']) && !empty($callback['middlewares'])) {
                foreach ($callback['middlewares'] as $middleware) {
                    if (!$this->checkMiddleware($middleware)) {
                        return response()->redirectBack();
                    }
                }
            }

            if (isset($callback[2]) && is_subclass_of($callback[2], Request::class)) {
                $request = new $callback[2]();
            }

            return call_user_func([$controller, $method], $request);
        }
    }

    public function renderView($view, $params = [], $layout = 'main')
    {
        $viewPath = str_replace('.', '/', $view);

        $viewContent = $this->renderOnlyView($viewPath, $params);
        $layoutContent = $this->layoutContent($layout);
        return str_replace('{{ content }}', $viewContent, $layoutContent);
    }

    public function renderContent($viewContent)
    {
        $layoutContent = $this->layoutContent('main');
        return str_replace('{{ content }}', $viewContent, $layoutContent);
    }

    protected function layoutContent($layoutFile)
    {
        ob_start();
        include_once Application::$rootDir . "/views/layouts/$layoutFile.php";
        return ob_get_clean();
    }

    protected function renderOnlyView($view, $params)
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        ob_start();
        include_once Application::$rootDir . "/views/$view.php";
        return ob_get_clean();
    }

    private function checkMiddleware(string $className)
    {
        $instance = new $className();
        return $instance->run();
    }
}
