<?php

// helps prevent pass invalid values
declare(strict_types=1);

namespace core;

class Router
{
    /*
     * about/john OR
     * /about/john OR
     * /about/john/
     * are all valid paths and are being normalized to /about/john/
     */
        private function normalizePath(string $path) : string {
            $path = trim($path, '/');
            $path = "/{$path}";

            // any sequence of 2 or more '/' replaced with a singular '/'
            preg_replace("#[/]{2,}#", '/', $path);
            return $path;
        }
    // array for storing added routes
    private array $routes = [];

    // middlewares are not going to be used in this project
    public function add(string $method, string $path, array $controller){

        $path = $this->normalizePath($path);

        //append normalized path and params to list of routes
        $this->routes[] = [
            'path' => $path,
            'method' => strtoupper($method),
            'controller' => $controller,
        ];
}
    public function dispatch(string $path){

        $path = $this->normalizePath($path);
        $method = strtoupper($_SERVER['REQUEST_METHOD']);
        foreach ($this->routes as $route) {

            // if it's not the right path, skip over it
            if (!preg_match("#^{$route['path']}$#", $path) || $route['method'] !== $method) {
                continue;
            }

            // if it's the right path, instantiate the controller and run it
            [$class, $function] = $route['controller'];
            $controllerInstance = new $class;
            $controllerInstance->{$function}();
            break;
        }
    }

}