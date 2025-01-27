<?php

namespace Src;

class Router
{
    private $routes = [];
    private $baseUrl;

    public function __construct()
    {
        $config = require __DIR__ . '/../config/app.php';
        $this->baseUrl = $config['base_url'];
    }

    // Add a new route
    public function add($method, $route, $callback)
    {
        $this->routes[] = [
            'method' => $method,
            'route' => $route,
            'callback' => $callback
        ];
    }

    // Match the request URI to the route
    public function match($method, $uri)
    {
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['route'] === $uri) {
                return $route['callback'];
            }
        }

        return null;
    }

    // Dispatch the request to the correct route handler
    public function dispatch($method, $uri)
    {
        $uri = str_replace($this->baseUrl, '', $uri);  // This removes /testphp part

        // $uri = ltrim($uri, '/');  // Ensures we get the clean route name, like 'about'

        $callback = $this->match($method, $uri);

        if ($callback) {
            call_user_func($callback);
        } else {
            echo "404 Not Found";
        }
    }
}
