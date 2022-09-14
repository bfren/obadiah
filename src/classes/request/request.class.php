<?php

namespace Feeds\Request;

use Feeds\App;
use Feeds\Config\Config as C;
use Feeds\Helpers\Arr;

App::check();

class Request
{
    /**
     * Admin user session key.
     */
    private const ADMIN = "admin";

    /**
     * Authenticated user session key.
     */
    private const AUTH = "auth";

    /**
     * Login attempt count session key.
     */
    private const COUNT = "count";

    /**
     * True if the current request is authenticated.
     *
     * @var bool
     */
    public static bool $auth;

    /**
     * True if the request is marked with a debug flag.
     *
     * @var bool
     */
    public static bool $debug;

    /**
     * Request method (e.g. POST).
     *
     * @var string
     */
    public static string $method;

    /**
     * Request URI path.
     *
     * @var string
     */
    public static string $uri;

    /**
     * Set request values.
     *
     * @return void
     */
    public static function init(): void
    {
        self::$auth = Arr::get($_SESSION, self::AUTH) === true || Arr::get($_GET, "api") == C::$login->api;
        self::$debug = isset($_GET["debug"]);
        self::$method = Arr::get($_SERVER, "REQUEST_METHOD");
        self::$uri = Arr::get($_SERVER, "REQUEST_URI");
    }

    /**
     * Mark request as authorised and reset failed login attempts.
     *
     * @param bool $admin               If true, the session will be given admin credentials.
     * @return void
     */
    public static function authorise(bool $admin = false): void
    {
        // mark session as authenticated
        $_SESSION[self::AUTH] = true;

        // mark session as admin
        if ($admin) {
            $_SESSION[self::ADMIN] = true;
        }

        // reset login attempts
        unset($_SESSION[self::COUNT]);
    }

    /**
     * Deny access by unsetting session variable and keeping track of failed attempts.
     *
     * @return void
     */
    public static function deny(): void
    {
        // unset auth session value
        unset($_SESSION[self::AUTH]);
        unset($_SESSION[self::ADMIN]);

        // keep track of failed login attempts
        if (isset($_SESSION[self::COUNT])) {
            $_SESSION[self::COUNT]++;
        } else {
            $_SESSION[self::COUNT] = 1;
        }
    }

    /**
     * Get the number of failed login attempts so far.
     *
     * @return int
     */
    public static function get_login_attempts(): int
    {
        if (isset($_SESSION[self::COUNT])) {
            return $_SESSION[self::COUNT];
        } else {
            return 0;
        }
    }

    /**
     * Returns true if the current session has admin credentials.
     *
     * @return bool                     Whether or not the current session has admin credentials.
     */
    public static function is_admin(): bool
    {
        return isset($_SESSION[self::ADMIN]) && $_SESSION[self::ADMIN];
    }

    /**
     * Close session and redirect to $uri.
     *
     * @param string $uri               Redirect URI.
     * @param bool $include_path        If true, the request path will be included in the redirect URL.
     * @return void
     */
    public static function redirect(string $uri, bool $include_path = false): void
    {
        // add requested URI
        if ($include_path) {
            $uri = sprintf("%s?requested=%s", $uri, self::$uri);
        }

        // close session
        session_write_close();

        // redirect
        header(sprintf("Location: %s", $uri));
        exit;
    }
}
