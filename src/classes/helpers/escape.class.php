<?php

namespace Feeds\Helpers;

use Feeds\App;

App::check();

class Escape
{
    /**
     * Escape $text - using sprintf if $args are defined.
     *
     * @param string $text                  Text (or sprintf format) to be printed.
     * @param array $args                   Optional arguments to use for sprintf.
     * @return string                       Safe (escaped) text.
     */
    public static function get(string $text, mixed ...$args): string
    {
        // if arguments have been provided, use sprintf
        if (count($args) > 0) {
            $formatted = sprintf($text, ...$args);
            return _e($formatted);
        }

        // convert HTML characters so the text is safe are safe
        return htmlspecialchars($text, ENT_QUOTES);
    }

    /**
     * Safely echo $text - using sprintf if $args are defined.
     *
     * @param string $text                  Text (or sprintf format) to be printed.
     * @param array $args                   Optional arguments to use for sprintf.
     * @return void
     */
    public static function echo(string $text, mixed ...$args): void
    {
        echo self::get($text, $args);
    }
}
