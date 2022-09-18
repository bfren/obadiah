<?php

namespace Feeds\Helpers;

use Feeds\App;

App::check();

class Input
{
    /**
     * Safely get a boolean value from $_GET.
     *
     * @param string $key               Array key.
     * @param bool $default             Optional default value.
     * @return bool                     Value or default value.
     */
    public static function get_bool(string $key, bool $default = false): bool
    {
        return self::bool(INPUT_GET, $key, $default);
    }

    /**
     * Safely get a string value from $_GET.
     *
     * @param string $key               Array key.
     * @param string $default           Optional default value.
     * @return string                   Value or default value.
     */
    public static function get_string(string $key, string $default = ""): string
    {
        return self::string(INPUT_GET, $key, $default);
    }

    /**
     * Safely get a string value from $_POST.
     *
     * @param string $key               Array key.
     * @param string $default           Optional default value.
     * @return string                   Value or default value.
     */
    public static function post_string(string $key, string $default = ""): string
    {
        return self::string(INPUT_POST, $key, $default);
    }

    /**
     * Safely get a string value from $_SERVER.
     *
     * @param string $key               Array key.
     * @param string $default           Optional default value.
     * @return string                   Value or default value.
     */
    public static function server_string(string $key, string $default = ""): string
    {
        return self::string(INPUT_SERVER, $key, $default);
    }

    /**
     * Get and sanitise a boolean value from a super global.
     *
     * @param int $type                 Global type.
     * @param string $key               Array key.
     * @param bool $default             Optional default value.
     * @return bool                     Value or default value.
     */
    private static function bool(int $type, string $key, bool $default = false): bool
    {
        return filter_input($type, $key, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?: $default;
    }

    /**
     * Get and sanitise a string value from a super global.
     *
     * @param int $type                 Global type.
     * @param string $key               Array key.
     * @param string $default           Optional default value.
     * @return string                   Value or default value.
     */
    private static function string(int $type, string $key, string $default = ""): string
    {
        // get value
        $input = filter_input($type, $key, FILTER_UNSAFE_RAW, FILTER_NULL_ON_FAILURE | FILTER_FLAG_STRIP_BACKTICK) ?: $default;

        // return sanitised string
        return Sanitise::text_input($input);
    }
}
