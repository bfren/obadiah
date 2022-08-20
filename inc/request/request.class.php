<?php

namespace Feeds\Request;

use Feeds\Config\Config as C;
use Feeds\Helpers\Arr;

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
    public static bool $method;

    /**
     * Request URI path.
     *
     * @var string
     */
    public static string $uri;

    /**
     * Set request values and load config.
     *
     * @param string $cwd               Current working directory.
     * @return void
     */
    public static function init(string $cwd): void
    {
        // start session
        session_start();

        // load config
        C::load($cwd);

        // set request values
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
     * @return void
     */
    public static function redirect(string $uri): void
    {
        session_write_close();
        header(sprintf("Location: %s", $uri));
    }
}
