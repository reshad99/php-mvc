<?php

namespace app\core;

class Application
{
    public static string $rootDir;
    public Router $router;
    public IRequest $request;
    public Response $response;
    public static Application $app;

    public function __construct(string $rootPath)
    {
        self::$rootDir = $rootPath;
        self::$app = $this;
        $this->request = new Request;
        $this->response = new Response;
        $this->router = new Router($this->request, $this->response);
    }

    public function run()
    {
        echo $this->router->resolve();
    }
}
