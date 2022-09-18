<?php

namespace Feeds\Router;

use Feeds\App;
use Feeds\Helpers\Arr;
use Feeds\Pages\Error\Error;
use Feeds\Response\Action;

App::check();

class Router
{
    public static array $routes;

    public static function init()
    {
        self::$routes = array("home" => "Home");
    }

    public static function map(string $page, string $class_name): void
    {
        self::$routes[$page] = sprintf('\Feeds\Pages\%1$s\%1$s', $class_name);
    }

    public static function dispatch(string $page, string $action): Action
    {
        $class_name = Arr::get(self::$routes, $page);

        if ($class_name) {
            $class = new $class_name();

            if (method_exists($class, $action)) {
                return $class->$action();
            }

            if (method_exists($class, "index")) {
                return $class->index();
            }
        }

        $error = new Error();
        return $error->not_found();
    }
}
