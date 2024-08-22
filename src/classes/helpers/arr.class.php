<?php

namespace Obadiah\Helpers;

use Obadiah\App;

App::check();

class Arr
{
    /**
     * Safely get a value from an associative array.
     *
     * @param array $array              Array of values.
     * @param mixed $key                The key to search for.
     * @param mixed $default            Default value if key does not exist.
     * @return mixed                    Key value, or $default if key does not exist.
     */
    public static function get(array $array, mixed $key, mixed $default = null): mixed
    {
        if (array_key_exists($key, $array) && $array[$key]) {
            return $array[$key];
        }

        return $default;
    }

    /**
     * Safely get a boolean value from an associative array.
     *
     * @param array $array              Array of values.
     * @param string $key               The key to search for.
     * @param boolean $default          Default value if key does not exist.
     * @return boolean                  Key value, or $default if key does not exist.
     */
    public static function get_boolean(array $array, string $key, bool $default = false): bool
    {
        if (array_key_exists($key, $array) && $array[$key]) {
            return in_array($array[$key], array(true, "true", "yes", 1, "1"));
        }

        return $default;
    }

    /**
     * Safely get an integer value from an associative array.
     *
     * @param array $array              Array of values.
     * @param string $key               The key to search for.
     * @param boolean $default          Default value if key does not exist.
     * @return boolean                  Key value, or $default if key does not exist.
     */
    public static function get_integer(array $array, string $key, int $default = 0): int
    {
        if (array_key_exists($key, $array) && $array[$key] && is_numeric($array[$key])) {
            return intval($array[$key]);
        }

        return $default;
    }

    /**
     * Safely get a value from an associative array, and then sanitise the input.
     * If the value is not a string, an empty string will be returned.
     *
     * @param array $array              Array of values.
     * @param mixed $key                The key to search for.
     * @param mixed $default            Default value if key does not exist.
     * @return mixed                    Key value, or $default if key does not exist.
     */
    public static function get_sanitised(array $array, mixed $key, mixed $default = null): string
    {
        // get value from the array
        $value = self::get($array, $key, $default);
        if (!is_string($value)) {
            return "";
        }

        // return sanitised string value
        return Sanitise::text_input((string) $value);
    }

    /**
     * Transform each element in $array using $callback.
     *
     * @param array $array              Array to map.
     * @param null|callable $callback   Callback function (should return a transformed value).
     * @return array                    Array of transformed elements.
     */
    public static function map(array $array, ?callable $callback = null)
    {
        return array_filter(array_map($callback, $array));
    }

    /**
     * Return matching elements of $array by using $callback. If $callback is null,
     * it will remove empty elements.
     *
     * @param array $array              Array to search.
     * @param null|callable $callback   Callback function (should return bool),
     * @return array                    Array of matching elements.
     */
    public static function match(array $array, ?callable $callback = null): array
    {
        return array_values(array_filter($array, $callback));
    }
}
