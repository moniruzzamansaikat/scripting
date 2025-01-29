<?php

namespace Src;

use ErrorException;
use ReflectionClass;
use ReflectionMethod;
use Src\Attributes\Auth;
use Src\Attributes\Route;

class Router
{
    private $routes = [];
    private $baseUrl;
    private $middleware = [];
    private $routeGroups = [];

    public function __construct()
    {
        $config = require __DIR__ . '/../config/app.php';
        $this->baseUrl = $config['base_url'];
    }

    public function registerControllersAutomatically()
    {
        $controllerNamespace = 'Src\\Controllers\\';
        $controllerPath = __DIR__ . '/Controllers/';

        foreach (glob($controllerPath . '*.php') as $file) {
            $className = basename($file, '.php');
            $fullClassName = $controllerNamespace . $className;

            if (class_exists($fullClassName)) {
                $controllerInstance = new $fullClassName();
                $this->registerRoutesFromAttributes($controllerInstance);
            }
        }
    }

    private function registerRoutesFromAttributes($controller)
    {
        $reflectionClass = new ReflectionClass($controller);

        foreach ($reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            foreach ($method->getAttributes(Route::class) as $attribute) {
                $routeInstance = $attribute->newInstance();
                $this->add($routeInstance->method, $routeInstance->path, [$controller, $method->getName()]);
            }
        }
    }

    private function applyAuthAttribute($callback)
    {
        $reflection = new ReflectionMethod($callback[0], $callback[1]); // Get the controller and method

        foreach ($reflection->getAttributes(Auth::class) as $attribute) {
            $auth = $attribute->newInstance();

            // Call the redirect logic in the Auth attribute (for example)
            $auth->handle();
        }
    }

    /**
     * Add a route.
     *
     * @param string $method
     * @param string $route
     * @param callable|array $callback
     * @param array $middleware
     */
    public function add($method, $route, $callback, $middleware = [])
    {
        // Apply group prefix if any
        if (!empty($this->routeGroups)) {
            $lastGroup = end($this->routeGroups);
            $route = $lastGroup['prefix'] . $route;
            $middleware = array_merge($lastGroup['middleware'], $middleware);
        }

        $this->routes[] = [
            'method' => $method,
            'route' => $route,
            'callback' => $callback,
            'middleware' => $middleware,
        ];
    }

    /**
     * Group routes with a common prefix or middleware.
     *
     * @param string $prefix
     * @param array $middleware
     * @param callable $callback
     */
    public function group($prefix, $middleware, $callback)
    {
        $this->routeGroups[] = [
            'prefix' => $prefix,
            'middleware' => $middleware,
        ];

        call_user_func($callback, $this);

        array_pop($this->routeGroups);
    }

    /**
     * Match a route based on method and URI.
     *
     * @param string $method
     * @param string $uri
     * @return array|null
     */
    public function match($method, $uri)
    {
        foreach ($this->routes as $route) {
            $pattern = $this->convertRouteToPattern($route['route']);

            if ($route['method'] === $method && preg_match($pattern, $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY); // Extract named parameters
                return [
                    'callback' => $route['callback'],
                    'middleware' => $route['middleware'],
                    'params' => $params,
                ];
            }
        }

        return null;
    }

    /**
     * Dispatch the request to the appropriate route.
     *
     * @param string $method
     * @param string $uri
     */
    public function dispatch($method, $uri)
    {
        $uri = str_replace($this->baseUrl, '', $uri); // Remove base URL

        $matchedRoute = $this->match($method, $uri);

        if ($matchedRoute) {
            $callback = $matchedRoute['callback'];
            $middleware = $matchedRoute['middleware'];
            $params = $matchedRoute['params'];

            // Apply middleware
            $this->applyMiddleware($middleware);

            $this->applyAuthAttribute($callback);

            // Pass params to the callback
            if (is_array($callback) && is_callable($callback)) {
                call_user_func_array($callback, $params);
            } elseif (is_callable($callback)) {
                call_user_func($callback, $params);
            } else {
                throw new ErrorException("Invalid callback for route: $uri");
            }
        } else {
            header("HTTP/1.0 404 Not Found");
            echo "404 Not Found";
        }
    }

    /**
     * Convert a route with dynamic parameters to a regex pattern.
     *
     * @param string $route
     * @return string
     */
    private function convertRouteToPattern($route)
    {
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $route);
        return '#^' . $pattern . '$#';
    }

    /**
     * Apply middleware to the request.
     *
     * @param array $middleware
     */
    private function applyMiddleware($middleware)
    {
        foreach ($middleware as $mw) {
            if (is_callable($mw)) {
                call_user_func($mw);
            } else {
                throw new ErrorException("Invalid middleware provided.");
            }
        }
    }
}
