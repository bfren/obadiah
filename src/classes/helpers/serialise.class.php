<?php

namespace Obadiah\Helpers;

use Obadiah\App;

App::check();

class Serialise
{
    /**
     * Indicate that you want to use JSON serialisation.
     */
    public const SERIALISE_JSON = 1 << 1;

    /**
     * Serialise a value to JSON or fall back to PHP serialize for complex objects.
     * Prefix "j:" indicates JSON format, "s:" indicates PHP object serialisation format.
     *
     * @param mixed $value              Value to serialise.
     * @param int $type                 The type of serialisation to use (0 = default).
     * @return string                   Serialised value.
     */
    public static function store(mixed $value, int $type = 0): string
    {
        if ($type == self::SERIALISE_JSON) {
            $json = json_encode($value, JSON_PRESERVE_ZERO_FRACTION | JSON_UNESCAPED_SLASHES);
            if ($json !== false) {
                return "j:" . $json;
            }
        }

        // use PHP object serialisation by default or if JSON serialisation fails
        return "s:" . serialize($value);
    }

    /**
     * Parse a value from JSON or PHP serialisation format and return the object.
     *
     * @param string $data              Serialised value.
     * @return mixed                    Deserialised value.
     */
    public static function parse(string $data): mixed
    {
        if (str_starts_with($data, "j:")) {
            // JSON format
            return json_decode(substr($data, 2), true);
        } elseif (str_starts_with($data, "s:")) {
            // PHP object serialisation format
            return unserialize(substr($data, 2));
        } else {
            // legacy format (no prefix) - assume PHP object serialisation
            return unserialize($data);
        }
    }
}
