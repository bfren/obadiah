<?php

namespace Obadiah\Helpers;

use DateTimeImmutable;
use Obadiah\App;
use Obadiah\Request\Request;

App::check();

class Log
{
    /**
     * Log a debug message - using sprintf if $args are defined.
     *
     * @param string|null $text             Message (or sprintf format) to be logged.
     * @param mixed $args                   Optional arguments to use for sprintf.
     * @return void
     */
    public static function debug(?string $text, mixed ...$args): void
    {
        // if string is null do nothing
        if ($text === null) {
            return;
        }

        // if debug flag is not set do nothing
        if (!Request::$debug) {
            return;
        }

        // if arguments have been provided, use sprintf
        $message = sprintf("%s [debug] " . $text, date("c"), ...$args);

        // output to stdout
        file_put_contents("php://stdout", $message);
    }

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
