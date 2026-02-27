<?php

namespace Obadiah\Helpers;

use Obadiah\App;

App::check();

class Serialise
{
    /**
     * Serialise a value to JSON or fall back to PHP serialize for complex objects.
     *
     * @param mixed $value              Value to serialise.
     * @return string                   Serialised value.
     */
    public static function store(mixed $value): string
    {
        // try JSON first for better performance
        $json = json_encode($value, JSON_PRESERVE_ZERO_FRACTION | JSON_UNESCAPED_SLASHES);
        if ($json !== false) {
            // prefix with 'j:' to indicate JSON format
            return 'j:' . $json;
        }

        // fall back to PHP object serialisation for complex objects
        return 's:' . serialize($value);
    }

    /**
     * Parse a value from JSON or PHP serialize format and return the object.
     *
     * @param string $data              Serialised value.
     * @return mixed                    Deserialised value.
     */
    public static function parse(string $data): mixed
    {
        if (str_starts_with($data, 'j:')) {
            // JSON format
            return json_decode(substr($data, 2), true);
        } elseif (str_starts_with($data, 's:')) {
            // PHP object serialisation format
            return unserialize(substr($data, 2));
        } else {
            // legacy format (no prefix) - assume PHP object serialisation
            return unserialize($data);
        }
    }
}
