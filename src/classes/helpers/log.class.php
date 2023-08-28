<?php

namespace Feeds\Helpers;

use Feeds\App;

App::check();

class Log
{
    /**
     * Log an error - using sprintf if $args are defined.
     *
     * @param null|string $error            Error message (or sprintf format) to be logged.
     * @param array $args                   Optional arguments to use for sprintf.
     * @return void
     */
    public static function error(?string $error, mixed ...$args): void
    {
        // if string is null do nothing
        if ($error === null) {
            return;
        }

        // add slashes - if arguments have been provided, use sprintf
        $message = addslashes(count($args) > 0 ? sprintf($error, ...$args) : $error);

        // send to error log
        error_log($message);
    }
}
