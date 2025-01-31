<?php

namespace Src;

use Pimple\Container;
use Src\Router;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Application
{
    private $container;

    public function __construct()
    {
        $this->container = new Container();
        $this->registerServices();
    }

    private function registerServices()
    {
        $this->container['logger'] = function () {
            $log = new Logger('request_logger');
            $log->pushHandler(new StreamHandler(__DIR__ . '/../logs/request_time.log', Logger::INFO));
            return $log;
        };

        $this->container['router'] = function () {
            $router = new Router();
            $router->registerControllersAutomatically();
            return $router;
        };

        $this->container['config'] = function () {
            return require __DIR__ . '/../config/app.php';
        };
    }

    public function run()
    {
        $startTime = microtime(true);

        $router = $this->container['router'];
        $config = $this->container['config'];
        $log    = $this->container['logger'];

        $baseUrl = $config['base_url'];
        $method  = $_SERVER['REQUEST_METHOD'];
        $uri     = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $router->dispatch($method, $uri);

        $endTime     = microtime(true);
        $requestTime = ($endTime - $startTime) * 1000;

        $awesome = str_replace($baseUrl, '', $uri);
        $log->info(strtoupper($method) . ": {$awesome} took " . round($requestTime, 2) . " ms");
    }

    public function getContainer()
    {
        return $this->container;
    }
}
