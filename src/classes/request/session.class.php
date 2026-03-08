<?php

namespace Obadiah\Request;

use Obadiah\App;
use Obadiah\Config\Config as C;
use Obadiah\Helpers\Arr;

App::check();

class Session
{
    /**
     * Admin user session key.
     */
    private const ADMIN = "_admin";

    /**
     * Authenticated user session key.
     */
    private const AUTH = "_auth";

    /**
     * Login attempt count session key.
     */
    private const COUNT = "_count";

    /**
     * CSRF Token session key.
     */
    private const CSRF_TOKEN = "_csrf";

    /**
     * Access was denied.
     */
    private const DENIED = "_denied";

    /**
     * True if the current request is an authenticated administrator.
     *
     * @var bool
     */
    public bool $is_admin;

    /**
     * True if the current request is authenticated.
     *
     * @var bool
     */
    public bool $is_authorised;

    /**
     * The number of unsuccessful login attempts for the current session.
     *
     * @var int
     */
    public int $login_attempts;

    /**
     * Set session values.
     *
     * @return void
     */
    public function __construct()
    {
        // check for previously authenticated sessions
        $is_authorised = Arr::get_boolean($_SESSION, self::AUTH);

        // allow direct login via API
        $is_api_login = C::$login->api && hash_equals(C::$login->api, Request::$get->string("api"));

        // store authorisation status
        $this->is_authorised = $is_authorised || $is_api_login;

        // store admin permissions
        $this->is_admin = $is_authorised && Arr::get_boolean($_SESSION, self::ADMIN);

        // brute force protection
        $this->login_attempts = Arr::get($_SESSION, self::COUNT, 0);
    }

    /**
     * Mark request as authorised and reset failed login attempts.
     *
     * @param bool $admin               If true, the session will be given admin credentials.
     * @return void
     */
    public function authorise(bool $admin = false): void
    {
        // regenerate session ID to prevent session fixation attacks
        session_regenerate_id(true);

        // mark session as authenticated
        $_SESSION[self::AUTH] = true;

        // mark session as admin
        if ($admin) {
            $_SESSION[self::ADMIN] = true;
        }

        // unset login attempts
        unset($_SESSION[self::COUNT]);

        // unset denied
        unset($_SESSION[self::DENIED]);
    }

    /**
     * Log the user out of the current session.
     *
     * @return void
     */
    public function logout(): void
    {
        // unset auth values
        unset($_SESSION[self::AUTH]);
        unset($_SESSION[self::ADMIN]);
        unset($_SESSION[self::CSRF_TOKEN]);
    }

    /**
     * Deny access by unsetting session variable and keeping track of failed attempts.
     *
     * @return void
     */
    public function deny(): void
    {
        // logout
        $this->logout();

        // set denied value
        $_SESSION[self::DENIED] = true;

        // keep track of failed login attempts
        $_SESSION[self::COUNT] = $this->login_attempts + 1;
    }

    /**
     * Returns true if the user was denied access to a resource.
     *
     * @return bool                     True if the user was denied access.
     */
    public function is_denied(): bool
    {
        return isset($_SESSION[self::DENIED]);
    }

    /**
     * Store CSRF token.
     *
     * @param string $token             CSRF token value.
     * @return void
     */
    public function set_csrf(string $token): void
    {
        $_SESSION[self::CSRF_TOKEN] = $token;
    }

    /**
     * Retrieve CSRF token.
     *
     * @return null|string              CSRF token value or null if not set.
     */
    public function get_csrf(): ?string
    {
        if (isset($_SESSION[self::CSRF_TOKEN])) {
            return $_SESSION[self::CSRF_TOKEN];
        }

        return null;
    }
}
