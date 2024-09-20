<?php

namespace Obadiah\Helpers;

use Obadiah\App;

App::check();

class Psalms
{
    /**
     * Return singular or plural Psalm' or 'Psalms' based on $psalms.
     *
     * @param string[]|string $psalms               The psalms to check.
     * @return string                               Either 'Psalm' or 'Psalms'.
     */
    public static function pluralise(array|string $psalms): string
    {
        // split string by comma
        if (is_string($psalms)) {
            $psalms = Arr::map(explode(",", $psalms), "trim");
        }

        // return Psalm or Psalms based on the array count
        return match (count($psalms)) {
            1 => "Psalm",
            default => "Psalms"
        };
    }
}
