<?php

namespace Obadiah\Helpers;

use Obadiah\App;

App::check();

class Sanitise
{
    /**
     * Sanitise user text input so it is safe to use.
     * See https://developer.wordpress.org/reference/functions/_sanitize_text_fields/.
     *
     * @param mixed $input              Input string.
     * @return string                   Sanitised (safe) string.
     */
    public static function text_input(mixed $input): string
    {
        // ignore non-string input
        if (is_object($input) || is_array($input)) {
            return "";
        }

        // strip tags - including single less then signs
        $filtered = self::strip_tags((string) $input, true);
        if (strpos($filtered, "<") !== false) {
            $filtered = str_replace("<", "&lt;", $filtered);
        }

        // remove invalid octets
        $found = false;
        while (preg_match("/%[a-f0-9]{2}/i", $filtered, $match)) {
            $filtered = str_replace($match[0], "", $filtered);
            $found = true;
        }

        // strip out the whitespace that may now exist after removing the octets.
        if ($found) {
            $filtered = preg_replace('/ +/', ' ', $filtered);
        }

        // trim and return filtered string
        return trim($filtered);
    }

    /**
     * Strip all HTML tags from $string.
     * See https://developer.wordpress.org/reference/functions/wp_strip_all_tags/.
     *
     * @param string $string            String containing HTML tags.
     * @param bool $remove_breaks       Whether to remove left over link breaks and new lines.
     * @return string                   Processed string.
     */
    public static function strip_tags($string, $remove_breaks = false)
    {
        // strip tags (including script / style)
        $filtered = preg_replace("@<(script|style)[^>]*?>.*?</\\1>@si", "", $string);
        $filtered = strip_tags($filtered);

        // remove breaks
        if ($remove_breaks) {
            $filtered = preg_replace("/[\r\n\t ]+/", " ", $filtered);
        }

        // trim and return filtered string
        return trim($filtered);
    }
}
