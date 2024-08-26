<?php

namespace Obadiah\Helpers;

use Exception;
use Obadiah\App;

App::check();

class Arr
{
    /**
     * Replacement for array_key_exists - also checks that the value of $array[$key] is not null.
     *
     * @param mixed[] $array                        Array of values.
     * @param int|string $key                       Key to search for.
     * @return bool                                 True if $array contains $key and is not null.
     */
    public static function exists(array $array, int|string $key): bool
    {
        return array_key_exists($key, $array) && $array[$key] !== null;
    }

    /**
     * Safely get a value from an associative array.
     *
     * @template T
     * @param T[]|array<int|string, T> $array       Array of values.
     * @param int|string $key                       The key to search for.
     * @param T|null $default                       Default value if key does not exist.
     * @return T                                    Key value, or $default if key does not exist.
     */
    public static function get(array $array, int|string $key, mixed $default = null): mixed
    {
        if (self::exists($array, $key)) {
            return $array[$key];
        }

        return $default;
    }

    /**
     * Safely get a boolean value from an array.
     *
     * @param mixed[] $array                        Array of values.
     * @param int|string $key                       The key to search for.
     * @param boolean $default                      Default value if key does not exist.
     * @return boolean                              Key value, or $default if key does not exist.
     */
    public static function get_boolean(array $array, int|string $key, bool $default = false): bool
    {
        if (self::exists($array, $key)) {
            return in_array($array[$key], [true, "true", "yes", 1, "1"]);
        }

        return $default;
    }

    /**
     * Safely get an integer value from an array.
     *
     * @param mixed[] $array                        Array of values.
     * @param int|string $key                       The key to search for.
     * @param int $default                          Default value if key does not exist.
     * @return int                                  Key value, or $default if key does not exist.
     */
    public static function get_integer(array $array, int|string $key, int $default = 0): int
    {
        if (self::exists($array, $key) && is_numeric($array[$key])) {
            return intval($array[$key]);
        }

        return $default;
    }

    /**
     * Get a required value from an associative array.
     *
     * @param mixed[] $array                        Array of values.
     * @param int|string $key                       The key to search for.
     * @return mixed                                Key value if key does not exist.
     * @throws Exception                            When $key is not found in $array.
     */
    public static function get_required(array $array, int|string $key): mixed
    {
        if (self::exists($array, $key)) {
            return $array[$key];
        }

        throw new Exception("Required value $key not found.");
    }

    /**
     * Safely get a value from an array, and then sanitise the input.
     * If the value is not a string, an empty string will be returned.
     *
     * @param mixed[] $array                        Array of values.
     * @param int|string $key                       The key to search for.
     * @param string $default                       Default value if key does not exist.
     * @return string                               Key value, or $default if key does not exist.
     */
    public static function get_sanitised(array $array, int|string $key, string $default = null): string
    {
        // get value from the array
        $value = self::get($array, $key, $default);
        if (!is_string($value)) {
            return "";
        }

        // return sanitised string value
        return Sanitise::text_input($value);
    }

    /**
     * Transform each element in $array using $callback.
     *
     * @template T
     * @template U
     * @param T[] $array                            Array to map.
     * @param callable(T): U|null $callback         Callback function (should return a transformed value).
     * @return U[]                                  Array of transformed elements.
     */
    public static function map(array $array, ?callable $callback = null): array
    {
        return array_filter(array_map($callback, $array));
    }

    /**
     * Return matching elements of $array by using $callback. If $callback is null,
     * it will remove empty elements.
     *
     * @template T
     * @param T[] $array                            Array to search.
     * @param callable|null $callback               Callback function (should return bool),
     * @return T[]                                  Array of matching elements.
     */
    public static function match(array $array, ?callable $callback = null): array
    {
        return array_values(array_filter($array, $callback));
    }

    /**
     * Returns the key of the matching element in $array, or false if the element cannot be found.
     *
     * @template T
     * @param T[] $array                            Array to search.
     * @param T $value                              Value to search for.
     * @return int|string|false                     Key of the first found value, or false if not found.
     */
    public static function search(array $array, mixed $value): int|string|false
    {
        return array_search($value, $array);
    }
}
