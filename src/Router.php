<?php

namespace src;
use src\StatusCodes;

class Router
{
    protected $routes = [];

    public function add($method, $uri, $controller)
    {
        $this->routes[] = [
            'uri' => $uri,
            'controller' => $controller,
            'method' => $method,
        ];

        return $this;
    }

    public function get($uri, $controller)
    {
        return $this->add('GET', $uri, $controller);
    }

    public function post($uri, $controller)
    {
        return $this->add('POST', $uri, $controller);
    }

    public function delete($uri, $controller)
    {
        return $this->add('DELETE', $uri, $controller);
    }

    public function patch($uri, $controller)
    {
        return $this->add('PATCH', $uri, $controller);
    }

    public function put($uri, $controller)
    {
        return $this->add('PUT', $uri, $controller);
    }

    public function route($uri, $method)
    {
        foreach ($this->routes as $route) {
            if ($route['uri'] === $uri && $route['method'] === strtoupper($method)) {

                // if it's a controller class, run it's methods
                if (class_exists($route['controller'])) {
                    // https://stackoverflow.com/questions/41825895/php-how-to-dynamically-instantiate-a-class
                    // https://stackoverflow.com/questions/54891279/dynamically-calling-a-method-in-a-class
                    (new $route['controller'])->{$route['method']}();
                }
                // if it's not a controller class, it's a require file
                else {
                    return require $route['controller'];
                }
                die();
            }
        }
        $this->abort();
    }

    public function previousUrl()
    {
        return $_SERVER['HTTP_REFERER'];
    }

    protected function abort($code = StatusCodes::HTTP_NOT_FOUND)
    {
        http_response_code($code);
        echo "Couldn't find specified route";
        die();
    }
}
