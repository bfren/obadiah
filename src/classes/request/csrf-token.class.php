<?php

namespace Obadiah\Request;

use Obadiah\App;
use Obadiah\Helpers\Crypto;
use SensitiveParameter;

App::check();

class Csrf_Token
{
    /**
     * HTML 'name' for use in forms and validation.
     */
    public const NAME = "_csrf_token";

    /**
     * Generate and store a CSRF token.
     *
     * @return string                   CSRF token.
     */
    public static function generate(): string
    {
        // create token hash
        $token = Crypto::generate(32);

        // store for the current session
        Request::$session->set_csrf($token);

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
        return Request::$session->get_csrf() ?? self::generate();
    }

    /**
     * Validate a CSRF token against the session token.
     *
     * @param string $token             Token to validate.
     * @param bool $regenerate          If true, a new token will be generated on successful validation.
     * @return bool                     True if token is valid, false otherwise.
     */
    public static function validate(#[SensitiveParameter] string $token, bool $regenerate = true): bool
    {
        // check if token matches session token
        $known = Request::$session->get_csrf();
        if ($known === null) {
            return false;
        }

        // hash_equals stops information leakage when comparing strings directly
        $valid = hash_equals($known, $token);

        // regenerate token after validation
        if ($valid && $regenerate) {
            self::generate();
        }

        return $valid;
    }
}
