<?php

namespace Obadiah\Helpers;

use Obadiah\App;

App::check();

class Str
{
    /**
     * Wrapper for preg_replace with a single string, ensuring no null returns.
     *
     * @param string|string[] $search           Regex search pattern(s).
     * @param string|string[] $replace          Regex replace pattern(s).
     * @param string $subject                   Single subject strings.
     * @return string                           Single replacement strings - or the original $subject.
     */
    public static function replace(array|string $search, array|string $replace, string $subject): string
    {
        $result = preg_replace($search, $replace, $subject);
        if ($result === null) {
            return $subject;
        }

        return $result;
    }

    /**
     * Wrapper for preg_replace with multiple strings, ensuring no null returns.
     *
     * @param string|string[] $search           Regex search pattern(s).
     * @param string|string[] $replace          Regex replace pattern(s).
     * @param string[] $subject                 Multiple subject strings.
     * @return string[]                         Multiple replacement strings - or the original $subject.
     */
    public static function replace_all(array|string $search, array|string $replace, array $subject): array
    {
        $result = preg_replace($search, $replace, $subject);
        if (count($result) == 0) {
            return $subject;
        }

        return $result;
    }
}
