<?php

namespace Feeds\Rota\Filters;

use Feeds\Rota\Service;

defined("IDX") || die("Nice try.");

class Day implements Filter
{
    /**
     * Returns true if the service is on the specified day of the week.
     *
     * @param Service $service          Service object.
     * @param string $value             Day of the week number (Sunday is 1).
     * @return bool                     True if the service is on the specified day of the week.
     */
    public function apply(Service $service, string $value): bool
    {
        // if no time value is set, include the service
        if (!$value) {
            return true;
        }

        // compare the day of the week with the value
        return date("N", $service->timestamp) == $value;
    }
}
