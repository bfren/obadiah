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
     * @param bool $requires_auth                   If true, all page actions will require authentication.
     * @param bool $requires_admin                  If true, all page actions will require administrative privileges.
     * @return void
     */
    public static function map_endpoint(
        string $endpoint_class,
        ?string $uri_path = null,
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
        $key = $uri_path ?: strtolower($class->getShortName());
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
            return self::not_found();
        }

        // check authentication
        if ($route->requires_auth && !Request::$session->is_authorised) {
            return self::denied();
        }

        // check admin privileges
        if ($route->requires_admin && !Request::$session->is_admin) {
            return self::denied();
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
            return self::not_found();
        }

        // check for admin requirement
        if (!empty($action_method->getAttributes(Require_Admin::class)) && !Request::$session->is_admin) {
            return self::denied();
        }

        // run action, catching any errors
        try {
            $endpoint = $endpoint_class->newInstance();
            return $action_method->invoke($endpoint);
        } catch (Throwable $th) {
            _l_throwable($th);
            return self::server_error($th);
        }
    }

    /**
     * Deny access and redirect to login page.
     *
     * @return Redirect
     */
    private static function denied(): Redirect
    {
        Request::$session->deny();
        return new Redirect("/auth/login", include_path: true);
    }

    /**
     * Return 'Not Found' view.
     *
     * @return View                                 Error 'Not Found' view.
     */
    private static function not_found(): View
    {
        $error = new Error();
        return $error->not_found();
    }

    /**
     * Return 'Server Error' view.
     *
     * @return View                                 Error 'Server Error' view.
     */
    private static function server_error(Throwable $th): View
    {
        $error = new Error();
        return $error->server_error($th);
    }
}
