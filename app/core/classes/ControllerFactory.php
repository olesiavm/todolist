<?php

namespace App\Controller;

class ControllerFactory
{
    public static function create($application, $controllerName, $actionName = 'indexAction')
    {
        try {
            $class = "App\Controller\\" . $controllerName;
            if (!class_exists($class)) {
                throw new \Exception("Controller $class not found");
            }

            $controller = new $class($application);

            if (!method_exists($controller, $actionName)) {
                throw new \Exception("Method $actionName of controller $controllerName not found");
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $controller;
    }
}
