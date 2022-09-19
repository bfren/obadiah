<?php

namespace Feeds\Router;

use Feeds\App;
use Feeds\Helpers\Arr;
use Feeds\Pages\Error\Error;
use Feeds\Request\Request;
use Feeds\Response\Action;
use Feeds\Response\Redirect;
use Feeds\Response\View;
use ReflectionClass;
use ReflectionException;
use Throwable;

App::check();

class Router
{
    /**
     *
     * @var Route[]
     */
    public static array $routes;

    public static function init()
    {
        self::map("Home");
    }

    public static function map(
        string $class_name,
        ?string $page = null,
        bool $requires_auth = true,
        bool $requires_admin = false
    ): void {
        // build route
        $route = new Route(
            class: sprintf('\Feeds\Pages\%1$s\%1$s', $class_name),
            requires_auth: $requires_auth || $requires_admin,
            requires_admin: $requires_admin
        );

        // check class exists
        try {
            class_exists($route->class);
        } catch (Throwable $th) {
            App::die("Unable to find class %s.", $route->class);
        }

        // add route
        $key = $page ?: strtolower($class_name);
        self::$routes[$key] = $route;
    }

    public static function get(string $page): ?Route
    {
        return Arr::get(self::$routes, $page);
    }

    /**
     * Dispatch the current action.
     *
     * @return Action                   Route action.
     * @throws ReflectionException
     */
    public static function dispatch(): Action
    {
        // split URI to get page and action
        $parts = Arr::match(explode("/", Request::$uri));
        $page = Arr::get($parts, 0, "home");
        $action = Arr::get($parts, 1, "index");

        // get route
        $route = self::get($page);
        if (!$route) {
            return self::not_found();
        }

        // check authentication
        if ($route->requires_auth && !Request::$session->is_authorised) {
            return new Redirect("/auth/login", include_path: true);
        }

        // check admin
        if ($route->requires_admin && !Request::$session->is_admin) {
            return new Redirect("/auth/logout");
        }

        // ensure the class exists
        try {
            $class = new ReflectionClass($route->class);
        } catch (Throwable $th) {
            return self::not_found();
        }

        // create page object
        $page = $class->newInstance();

        // create action with HTTP method
        $action_method = sprintf("%s_%s", $action, strtolower(Request::$method));

        // return not found if the action does not exist on the page class
        if (!$class->hasMethod($action_method)) {
            return self::not_found();
        }

        // run action, catching any errors
        try {
            return $page->$action_method();
        } catch (Throwable $th) {
            return self::server_error($th);
        }
    }

    /**
     * Return 'Not Found' view.
     *
     * @return View                     Error 'Not Found' view.
     */
    private static function not_found(): View
    {
        $error = new Error();
        return $error->not_found();
    }

    /**
     * Return 'Server Error' view.
     *
     * @return View                     Error 'Server Error' view.
     */
    private static function server_error(Throwable $th): View
    {
        $error = new Error();
        return $error->server_error($th);
    }
}
