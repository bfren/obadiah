<?php

namespace Obadiah\Helpers;

use DateTimeImmutable;
use Exception;
use Obadiah\App;
use Obadiah\Config\Config as C;

App::check();

class DateTime
{
    /**
     * Create a DateTimeImmutable object, displaying any errors and exiting on failure.
     *
     * @param string $format                DateTime format.
     * @param string $datetime              DateTime string.
     * @return DateTimeImmutable            DateTimeImmutable object.
     * @throws Exception                    When DateTimeImmutable::createFromFormat fails.
     */
    public static function create(string $format, string $datetime, bool $with_timezone = false): DateTimeImmutable
    {
        // attempt to create DateTimeImmutable
        $dt = DateTimeImmutable::createFromFormat($format, $datetime, $with_timezone ? C::$events->timezone : null);

        // handle warnings and errors
        if ($dt === false) {
            _l("DateTimeImmutable errors: %s", print_r(DateTimeImmutable::getLastErrors(), true));
            throw new Exception(sprintf("Unable to create DateTime from format %s using input %s.", $format, $datetime));
        }

        // return
        return $dt;
    }
}
