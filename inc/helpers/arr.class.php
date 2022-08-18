<?php

namespace Feeds\Helpers;

class Arr
{
    /**
     * Safely get a value from an associative array.
     *
     * @param array $array              Array of values.
     * @param string $key               The key to search for.
     * @return mixed                    Key value, or empty string if key is not found.
     */
    public static function get(array $array, string $key)
    {
        if (array_key_exists($key, $array)) {
            return $array[$key];
        }

        return "";
    }
}
