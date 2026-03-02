<?php

namespace Obadiah\Request;

use Obadiah\App;

App::check();

class Csrf_Token
{
    /**
     * Session key for storing CSRF token.
     */
    private const SESSION_KEY = "_csrf_token";

    /**
     * Generate a CSRF token based on the current session ID.
     *
     * @return string                   CSRF token.
     */
    public static function generate(): string
    {
        // create token from session ID + random bytes for extra entropy
        $token = hash("sha256", session_id() . random_bytes(32));

        // store in session
        $_SESSION[self::SESSION_KEY] = $token;

        return $token;
    }

    /**
     * Get the current CSRF token, generating one if it doesn't exist.
     *
     * @return string                   CSRF token.
     */
    public static function get(): string
    {
        // return existing token or generate new one
        return $_SESSION[self::SESSION_KEY] ?? self::generate();
    }

    /**
     * Validate a CSRF token against the session token.
     *
     * @param string $token             Token to validate.
     * @return bool                     True if token is valid, false otherwise.
     */
    public static function validate(string $token): bool
    {
        // check if token matches session token
        $valid = isset($_SESSION[self::SESSION_KEY]) &&
                 hash_equals($_SESSION[self::SESSION_KEY], $token);

        // regenerate token after validation
        if ($valid) {
            self::generate();
        }

        return $valid;
    }

    /**
     * Refresh the CSRF token (generate new one).
     *
     * @return string                   New CSRF token.
     */
    public static function refresh(): string
    {
        return self::generate();
    }
}
