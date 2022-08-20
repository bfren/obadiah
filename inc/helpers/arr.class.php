<?php

namespace Feeds\Helpers;

defined("IDX") || die("Nice try.");

class Arr
{
    /**
     * Safely get a value from an associative array.
     *
     * @param array $array              Array of values.
     * @param string $key               The key to search for.
     * @param mixed $default            Default value if key does not exist.
     * @return mixed                    Key value, or empty string if key is not found.
     */
    public static function get(array $array, string $key, mixed $default = null): mixed
    {
        if (array_key_exists($key, $array) && $array[$key]) {
            return $array[$key];
        }

        return $default;
    }
}
