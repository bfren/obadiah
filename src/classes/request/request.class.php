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
     * Encapsulates $_COOKIES.
     *
     * @var Super_Global
     */
    public static Super_Global $cookies;

    /**
     * Encapsulates $_ENV.
     *
     * @var Super_Global
     */
    public static Super_Global $env;

    /**
     * Encapsulates $_FILES.
     *
     * @var array
     */
    public static array $files;

    /**
     * Encapsulates $_GET.
     *
     * @var Super_Global
     */
    public static Super_Global $get;

    /**
     * Encapsulates $_POST.
     *
     * @var Super_Global
     */
    public static Super_Global $post;

    /**
     * Encapsulates $_SERVER.
     *
     * @var Super_Global
     */
    public static Super_Global $server;

    /**
     * Set request values.
     *
     * @return void
     */
    public static function init(): void
    {
        self::$cookies = new Super_Global(INPUT_COOKIE);
        self::$env = new Super_Global(INPUT_ENV);
        self::$files = $_FILES ?: array();
        self::$get = new Super_Global(INPUT_GET);
        self::$post = new Super_Global(INPUT_POST);
        self::$server = new Super_Global(INPUT_SERVER);

        self::$auth = Arr::get($_SESSION, self::AUTH, false) || self::$get->string("api") == C::$login->api;
        self::$debug = self::$get->bool("debug");
        self::$method = self::$server->string("REQUEST_METHOD");
        self::$uri = self::$server->string("REQUEST_URI");
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
        $_SESSION[self::COUNT] = Arr::get($_SESSION, self::COUNT, 0) + 1;
    }

    /**
     * Get the number of failed login attempts so far.
     *
     * @return int
     */
    public static function get_login_attempts(): int
    {
        return Arr::get($_SESSION, self::COUNT, 0);
    }

    /**
     * Returns true if the current session has admin credentials.
     *
     * @return bool                     Whether or not the current session has admin credentials.
     */
    public static function is_admin(): bool
    {
        return Arr::get($_SESSION, self::ADMIN, false);
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
