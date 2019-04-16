<?php
/**
 * Created by PhpStorm.
 * User: Pasha
 * Date: 08.12.2018
 * Time: 21:01
 */

namespace Core\Classes;

use App\Controller\Error;
use Core\Utils\Config;
use Core\Utils\Logger;

class Router
{

    private $routesConfig;
    private $controllerConfig;
    private $uri;

    public function __construct()
    {
        $this->routesConfig = Config::load('routes');
        $this->controllerConfig = Config::load('config')['controllers'];
        $this->uri = $_SERVER['REQUEST_URI'];
    }

    public function run() : void
    {
        $route = $this->findRoute();
        $routeController = explode('/', $route['controller']);
        $controllerPath = $this->controllerConfig['path'].$routeController[0].'.php';
        if (file_exists($controllerPath)) {
            $controller = $this->controllerConfig['namespace'].$routeController[0];
            $controller = new $controller();
            if (method_exists($controller, $routeController[1])) {
                if ($route['args']) {
                    $controller->{$routeController[1]}((new Request()));
                }
                else {
                    $controller->{$routeController[1]}();
                }
            }
            else {
                (new Error())->pageNotFound();
            }
        }

    }

    private function findRoute() : array
    {
        $route = array();
        $uri = strpos($this->uri, '?')?
            substr_replace(trim($this->uri), '', strpos($this->uri, '?')):
            trim($this->uri);
        $uriParts = explode('/', $uri);

        array_shift($uriParts);
        if (count($uriParts) < 3) {
            foreach ($this->routesConfig as $routeConfig) {
                if ($routeConfig['route'] == $uri) {
                    $route = $routeConfig;
                }
            }
        }
        if (count($uriParts) > 2 or count($route) == 0) {
            $route = $this->routesConfig['404'];
        }
        return $route;
    }

}