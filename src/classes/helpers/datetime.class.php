<?php

namespace Obadiah\Helpers;

use DateInterval;
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
     * @return DateTimeImmutable
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

    /**
     * Get a DateInterval spec string from a DateInterval object.
     * H/T Slava Fomin II https://stackoverflow.com/a/25371691.
     *
     * @param DateInterval $interval        DateInterval object.
     * @return string                       Interval spec string.
     */
    public static function get_interval_spec(DateInterval $interval): string
    {
        // reading all non-zero date parts
        $date = array_filter([
            "Y" => $interval->y,
            "M" => $interval->m,
            "D" => $interval->d
        ]);

        // reading all non-zero time parts
        $time = array_filter([
            "H" => $interval->h,
            "M" => $interval->i,
            "S" => $interval->s
        ]);

        $spec = "P";

        // adding each part to the spec-string
        foreach ($date as $key => $value) {
            $spec .= $value . $key;
        }
        if (count($time) > 0) {
            $spec .= "T";
            foreach ($time as $key => $value) {
                $spec .= $value . $key;
            }
        }

        return $spec;
    }

    /**
     * Create a DateTimeImmutable object representing the current time.
     *
     * @return DateTimeImmutable
     */
    public static function now(): DateTimeImmutable
    {
        return new DateTimeImmutable("now", C::$events->timezone);
    }
}
