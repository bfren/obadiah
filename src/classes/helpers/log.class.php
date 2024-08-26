<?php

namespace Obadiah\Helpers;

use Obadiah\App;

App::check();

class Log
{
    /**
     * Log an error - using sprintf if $args are defined.
     *
     * @param string|null $error            Error message (or sprintf format) to be logged.
     * @param mixed $args                   Optional arguments to use for sprintf.
     * @return void
     */
    public static function error(?string $error, mixed ...$args): void
    {
        // if string is null do nothing
        if ($error === null) {
            return;
        }

        // if arguments have been provided, use sprintf
        $message = count($args) > 0 ? sprintf($error, ...$args) : $error;

        // send to error log
        error_log($message);
    }
}
