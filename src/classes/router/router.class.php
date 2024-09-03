<?php

namespace Obadiah\Router;

use Obadiah\Admin\Require_Admin;
use Obadiah\App;
use Obadiah\Helpers\Arr;
use Obadiah\Pages\Error\Error;
use Obadiah\Pages\Home\Home;
use Obadiah\Request\Request;
use Obadiah\Response\Action;
use Obadiah\Response\Redirect;
use Obadiah\Response\View;
use ReflectionClass;
use Throwable;

App::check();

class Router
{
    /**
     * Mapped routes.
     *
     * @var array<string, Route>
     */
    public static array $routes;

    /**
     * Register home page by default.
     *
     * @return void
     */
    public static function init()
    {
        self::map_endpoint(Home::class);
    }

    /**
     * Map a route to an endpoint.
     *
     * Examples:
     *
     *      map_endpoint("Foo")                     Maps endpoint /foo to be handled by class \Obadiah\Pages\Foo\Foo.
     *
     *      map_endpoint("Foo", "bar")              Maps endpoint /bar to be handled by class \Obadiah\Pages\Foo\Foo.
     *
     * @template T of Endpoint
     * @param class-string<T> $endpoint_class       The name of the endpoint class to map (see \Obadiah\Api and \Obadiah\Pages).
     * @param string|null $uri_path                 Optional path override - by default $page_class will be used.
     * @param string $uri_prefix                    Optional path prefix - e.g. 'api/'.
     * @param bool $requires_auth                   If true, all page actions will require authentication.
     * @param bool $requires_admin                  If true, all page actions will require administrative privileges.
     * @return void
     */
    public static function map_endpoint(
        string $endpoint_class,
        ?string $uri_path = null,
        string $uri_prefix = "",
        bool $requires_auth = true,
        bool $requires_admin = false
    ): void {
        // build route
        $route = new Route(
            endpoint: $endpoint_class,
            requires_auth: $requires_auth || $requires_admin,
            requires_admin: $requires_admin
        );

        // add route
        $class = new ReflectionClass($endpoint_class);
        $key = sprintf("%s%s", $uri_prefix, $uri_path ?: strtolower($class->getShortName()));
        self::$routes[$key] = $route;
    }

    /**
     * Get a mapped route by endpoint name.
     *
     * @param string $endpoint                      Endpoint name.
     * @return Route|null                           Route, or null if the route cannot be found.
     */
    public static function get_route(string $endpoint): ?Route
    {
        return Arr::get(self::$routes, $endpoint);
    }

    /**
     * Get the current action by parsing the URI path.
     *
     * @return Action                               Route action.
     */
    public static function get_action(): Action
    {
        // split URI to get page and action
        $parts = Arr::match(explode("/", Request::$uri));
        $endpoint_name = Arr::get($parts, 0, "home");
        $action_name = Arr::get($parts, 1, "index");

        // get route
        $route = self::get_route($endpoint_name);
        if ($route === null) {
            _l("Route not found for endpoint '%s'.", $endpoint_name);
            return Error::not_found();
        }

        // check authentication
        if ($route->requires_auth && !Request::$session->is_authorised) {
            return Error::denied();
        }

        // check admin privileges
        if ($route->requires_admin && !Request::$session->is_admin) {
            return Error::denied();
        }

        // get endpoint class
        $endpoint_class = new ReflectionClass($route->endpoint);

        // the actual method is the action name plus HTTP method
        $method_name = sprintf("%s_%s", $action_name, strtolower(Request::$method));

        // return not found if the method does not exist in the endpoint class
        try {
            $action_method = $endpoint_class->getMethod($method_name);
        } catch (Throwable $th) {
            _l_throwable($th);
            return Error::not_found();
        }

        // check for admin requirement
        if (!empty($action_method->getAttributes(Require_Admin::class)) && !Request::$session->is_admin) {
            return Error::denied();
        }

        // run action, catching any errors
        try {
            $endpoint = $endpoint_class->newInstance();
            return $action_method->invoke($endpoint);
        } catch (Throwable $th) {
            _l_throwable($th);
            return Error::server_error($th);
        }
    }
}
