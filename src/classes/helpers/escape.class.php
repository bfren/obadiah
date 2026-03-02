<?php

namespace Obadiah\Helpers;

use Obadiah\App;

App::check();

class Escape
{
    /**
     * Escape $text - using sprintf if $args are defined.
     *
     * @param string|null $text             Text (or sprintf format) to be escaped.
     * @param mixed $args                   Optional arguments to use for sprintf.
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
        return trim(htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5));
    }

    /**
     * Safely echo $text - using sprintf if $args are defined.
     *
     * @param string|null $text             Text (or sprintf format) to be printed.
     * @param mixed $args                   Optional arguments to use for sprintf.
     * @return void
     */
    public static function echo_text(?string $text, mixed ...$args): void
    {
        print_r(self::get_text($text, ...$args));
    }

    /**
     * Get pre-escaped HTML content (no escaping applied) - using sprintf if $args are defined.
     * SECURITY NOTE: This function does NOT escape HTML. Only use with content that is already safe/escaped.
     * For unsafe user input, use get_text() instead.
     *
     * @param string|null $html             HTML (or sprintf format) - must already be safe/escaped.
     * @param mixed $args                   Optional arguments to use for sprintf.
     * @return string                       HTML content (unescaped).
     */
    public static function get_html(?string $html, mixed ...$args): string
    {
        // if html is null return a blank string
        if ($html === null) {
            return "";
        }

        // if arguments have been provided, use sprintf
        if (count($args) > 0) {
            $formatted = sprintf($html, ...$args);
            return self::get_html($formatted);
        }

        // return HTML as-is (no escaping - must be pre-escaped by caller)
        return trim($html);
    }

    /**
     * Echo pre-escaped HTML content (no escaping applied) - using sprintf if $args are defined.
     * SECURITY NOTE: This function does NOT escape HTML. Only use with content that is already safe/escaped.
     * For unsafe user input, use echo_text() instead.
     *
     * @param string|null $html             HTML (or sprintf format) - must already be safe/escaped.
     * @param mixed $args                   Optional arguments to use for sprintf.
     * @return void
     */
    public static function echo_html(?string $html, mixed ...$args): void
    {
        print_r(self::get_html($html, ...$args));
    }
}
