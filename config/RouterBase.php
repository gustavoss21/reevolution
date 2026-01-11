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

    public function formatURI($uri, $body){
        $uri = str_replace($this->routeBase, '', $uri);
        $uri = strtok($uri, '?'); // Remove query string
        if(empty($uri)) {
            $uri = '/';
        }

        if($this->method === 'GET' && !empty($body)){
            $uri .= '/';

            foreach($body as $key => $value){
                $uri .= '{'.$key.'}';
                if($value !== end($body)){
                    $uri .= '/';
                }
            }
        }

        return $uri;
    }

    public function setAction($uri, $body)
    {
        $uri = $this->formatURI($uri,$body);

        foreach ($this->routes[$this->method] as $route => $action) {
            $pattern = str_replace('/', '\/', $route);
            if (preg_match('/^' . $pattern . '$/', $uri, $matches)) {
                array_shift($matches); // Remove the full match
                // Extract controller and method
                list($this->controller, $this->action) = explode('@', $action);
            }
        }
        return $this;
    }

    function setBody($body)
    {
        if($this->method === 'POST'){
            $input = file_get_contents("php://input");
            $body = json_decode($input, true);
        }
        $this->body = $body;
        return $this;
    }

}