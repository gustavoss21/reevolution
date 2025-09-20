<?php

namespace Config;

class RouterBase
{
    public $routes = [];
    public $method = 'GET';
    public $routeBase = '/reevolution';
    public $controller;
    public $action;
    public $body = [];

    public function __construct() {
    }

    public function route()
    {
        $controllerName = 'Controllers\\'.$this->controller;

        if (class_exists($controllerName)) {
            $controller = new $controllerName();
            if (method_exists($controller, $this->action)) {
                return $controller->{$this->action}($this->body);
            } else {
                http_response_code(405);
                return ['error' => 'Method Not Allowed'];
            }
        } else {
            http_response_code(404);
            return ['error' => 'Not Found'];
        }
    }

    public function setMethod($method)
    {
        $this->method = strtoupper($method);
    }

    public function setAction($uri, $body)
    {
        $uri = str_replace($this->routeBase, '', $uri);
        
        foreach ($this->routes[$this->method] as $route => $action) {
            $pattern = preg_replace('/\{[a-zA-Z_][a-zA-Z0-9_]*\}/', '([a-zA-Z0-9_]+)', $route);
            $pattern = str_replace('/', '\/', $pattern);
            if (preg_match('/^' . $pattern . '$/', $uri, $matches)) {
                array_shift($matches); // Remove the full match
                // Extract controller and method
                list($this->controller, $this->action) = explode('@', $action);
                // If there are parameters in the route, add them to the body
                if (preg_match_all('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', $route, $paramNames)) {
                    foreach ($paramNames[1] as $index => $name) {
                        $this->body[$name] = $matches[$index];
                    }
                }
                // return self::route($controller, $method, $body);
                return $this;
            }
        }
        http_response_code(404);
        return ['error' => 'Not Found'];
    }

}