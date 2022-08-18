<?php

namespace Feeds\Rota\Filters;

use DateTime;
use Feeds\Rota\Service;

class Before implements Filter
{
    /**
     * Returns true if the service is before the specified date.
     *
     * @param Service $service          Service object.
     * @param string $value             A date formatted YYYY-MM-DD.
     * @return bool                     True if the service is before the specified date.
     */
    public function apply(Service $service, string $value): bool
    {
        // if no date value is set, include the service
        if (!$value) {
            return true;
        }

        // convert the date to a timestamp
        $dt = DateTime::createFromFormat("Y-m-d", $value);
        if ($dt) {
            return date("Y-m-d", $service->timestamp) <= $dt->format("Y-m-d");
        }

        // if we get here $value was an invalid date format so return false
        return false;
    }
}
