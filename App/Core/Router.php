<?php

namespace App\Core;
use App\Core\Helpers;
class Router
{
    public function __construct(
        private array $routes = []
    ) {}

    public function route(string $method, string $uri): bool
    {
        $routes = $this->routes;
        $uri = '/'.$uri;
        $params = [];

        if(!key_exists($uri, $routes)) {

                foreach (array_keys($routes) as $point) {

                    if( preg_match('/\/{(\w+)}/', $point, $matches)
                        && strcasecmp($method, $routes[$point]['method']) == 0
                        && count($uriArray = explode('/', $uri)) == count($pointArray = explode('/', $point)))
                    {
                        $paramName = $matches[1];
                        $paramIndex = array_search('{'.$paramName.'}', $pointArray);
                        $params[$paramName] = $uriArray[$paramIndex];

                        $instance = new $routes[$point]['controller'];
                        $action = $routes[$point]['action'];

                        $instance->$action($params);
                        return true;
                    }
                }

            Helpers::errorResponse('Route for this url '. $uri .' not register');
        }

        if(!strcasecmp($method, $routes[$uri]['method']) == 0) {
            Helpers::errorResponse('Route method '. $method .' not register for uri '. $uri);
        }

        $controller = $routes[$uri]['controller'];
        $action = $routes[$uri]['action'];

        if(!class_exists($controller)) {
            Helpers::errorResponse('Controller '. $controller .' not created');
        }

        $instance = new $controller;

        if(!method_exists($instance, $action)) {
            Helpers::errorResponse('Action '. $action .' not exist ' .$controller. 'controller');
        }

        $instance->$action();
        return true;
    }

    public function get(string $uri, array $action): void {

        $this->register($uri, $action, "GET");
    }

    public function post(string $uri, array $action): void {

        $this->register($uri, $action, "POST");
    }

    protected function register(string $uri, array $action, string $method): void {

        list($controller, $action) = $action;

        $this->routes[$uri] = [
            'method' => $method,
            'controller' => $controller,
            'action' => $action,
        ];
    }
}