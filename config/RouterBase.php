<?php

namespace Config;

use Dotenv\Util\Regex;

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

    public function formatURI($uri, &$body){
        $uri = str_replace($this->routeBase, '', $uri);
        $uri = strtok($uri, '?'); // Remove query string
        if(empty($uri)) {
            $uri = '/';
        }

        if($this->method === 'GET' && !empty($body)){
            $uri .= '/';

            foreach($body as $key => $value){
                $uri .= '{'.$key.'}';

                
                
                @[0=>$body_value,1=>$uri_parcial]      = $this->getUriInQuerySearch($value);

                if($uri_parcial){
                    $uri .= $uri_parcial;
                    $body[$key] = $body_value;
                }
                if(next($body)){
                    $uri .= '/';
                }


            }
        }

        return $uri;
    }

    public function setAction($uri, $body)
    {
        $uri = $this->formatURI($uri,$body);
        $this->setBody($body);


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

    function createRoute($serve, $body){
        $this->setMethod($serve['REQUEST_METHOD']);
        $this->setAction(
            $serve['REQUEST_URI'],
            $body
        );
    }

    function getUriInQuerySearch($body){
        $pathern_uri_last_part = '/(\/[\w_-][^\/]+)/';
        // $uri                   = preg_replace($pathern_uri_last_part, '$1', $body);
        $data                = preg_split($pathern_uri_last_part,$body,2, PREG_SPLIT_DELIM_CAPTURE);
        return array_slice($data,0,2);
        
    }

}