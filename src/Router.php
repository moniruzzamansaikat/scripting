<?php

namespace Src;

use ErrorException;

class Router
{
    private $routes = [];
    private $baseUrl;

    public function __construct()
    {
        $config = require __DIR__ . '/../config/app.php';
        $this->baseUrl = $config['base_url'];
    }

    public function add($method, $route, $callback)
    {
        $this->routes[] = [
            'method' => $method,
            'route' => $route,
            'callback' => $callback
        ];
    }

    public function match($method, $uri)
    {
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['route'] === $uri) {
                return $route['callback'];
            }
        }

        return null;
    }

    public function dispatch($method, $uri)
    {
        $uri = str_replace($this->baseUrl, '', $uri);  // This removes /testphp part

        $callback = $this->match($method, $uri);

        if ($callback) {
            call_user_func($callback);
        } else {
            header("HTTP/1.0 404 Not Found");
            echo "404 Not Found";
        }
    }
}
