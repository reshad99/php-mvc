<?php

namespace app\core;

class Application
{
    public static string $rootDir;
    public Router $router;
    public IRequest $request;
    public Response $response;
    public Database $database;
    public Auth $auth;
    public static Application $app;

    public function __construct(string $rootPath)
    {
        self::$rootDir = $rootPath;
        self::$app = $this;
        $this->request = new Request;
        $this->response = new Response;
        $this->database = new Database;
        $this->auth = Auth::getInstance();
        $this->router = new Router($this->request, $this->response);
    }

    public function run()
    {
        echo $this->router->resolve();
    }
}
