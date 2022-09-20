<?php

namespace Feeds\Request;

use Feeds\App;
use Feeds\Config\Config as C;
use Feeds\Helpers\Arr;

App::check();

class Session
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
     * Access was denied.
     */
    private const DENIED = "denied";

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
        $this->is_authorised = Arr::get($_SESSION, self::AUTH, false) || Request::$get->string("api") == C::$login->api;
        $this->is_admin = $this->is_authorised && Arr::get($_SESSION, self::ADMIN, false);
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
}
