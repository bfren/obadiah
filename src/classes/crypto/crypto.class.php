<?php

namespace Obadiah\Crypto;

use Obadiah\App;
use SensitiveParameter;
use Throwable;

App::check();

class Crypto
{
    /**
     * Hash a password using Argon2.
     *
     * @param string $password              The password to hash.
     * @return string                       Hashed password.
     */
    public static function hash_password(#[SensitiveParameter] string $password): string
    {
        try {
            return sodium_crypto_pwhash_str($password, SODIUM_CRYPTO_PWHASH_OPSLIMIT_MODERATE, SODIUM_CRYPTO_PWHASH_MEMLIMIT_MODERATE);
        } catch (Throwable $th) {
            _l_throwable($th);
            App::die("Unable to hash password.");
        }
    }

    /**
     * Verify a password against an argon2 hash.
     *
     * @param string $hash                  The hash to verify $password against.
     * @param string $password              The password to verify.
     * @return bool                         Whether or not the password was verified successfully.
     */
    public static function verify_password(string $hash, #[SensitiveParameter] string $password): bool
    {
        try {
            return sodium_crypto_pwhash_str_verify($hash, $password);
        } catch (Throwable $th) {
            _l_throwable($th);
            return false;
        }
    }
}
