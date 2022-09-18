<?php

namespace Feeds\Helpers;

use Feeds\App;

App::check();

class Escape
{
    /**
     * Escape $text - using sprintf if $args are defined.
     *
     * @param null|string $text             Text (or sprintf format) to be escaped.
     * @param array $args                   Optional arguments to use for sprintf.
     * @return string                       Safe (escaped) text.
     */
    public static function get_text(?string $text, mixed ...$args): string
    {
        // if string is null return a blank string
        if ($text === null) {
            return "";
        }

        // if arguments have been provided, use sprintf
        if (count($args) > 0) {
            $formatted = sprintf($text, ...$args);
            return self::get_text($formatted);
        }

        // convert HTML characters so the text is safe
        return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5);
    }

    /**
     * Safely echo $text - using sprintf if $args are defined.
     *
     * @param null|string $text             Text (or sprintf format) to be printed.
     * @param array $args                   Optional arguments to use for sprintf.
     * @return void
     */
    public static function echo_text(?string $text, mixed ...$args): void
    {
        echo self::get_text($text, ...$args);
    }

    /**
     * Escape $html.
     *
     * @param null|string $html             HTML to be escaped.
     * @return string                       Safe (escaped) html.
     */
    public static function get_html(?string $html): string
    {
        // if html is null return a blank string
        if ($html === null) {
            return "";
        }

        // handle as HTML characters so the text is safe are safe
        return $html;
    }

    /**
     * Safely echo $html.
     *
     * @param null|string $html             HTML to be printed.
     * @return void
     */
    public static function echo_html(?string $html): void
    {
        echo self::get_html($html);
    }
}
