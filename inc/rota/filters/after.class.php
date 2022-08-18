<?php

namespace Feeds\Rota\Filters;

use DateTime;
use Feeds\Config\Config;
use Feeds\Rota\Service;

defined("IDX") || die("Nice try.");

class After implements Filter
{
    /**
     * Returns true if the service is after the specified date.
     *
     * @param Service $service          Service object.
     * @param string $value             A date formatted YYYY-MM-DD.
     * @return bool                     True if the service is after the specified date.
     */
    public function apply(Service $service, string $value): bool
    {
        // if no date value is set, include the service
        if (!$value) {
            return true;
        }

        // convert the date to a timestamp
        $dt = DateTime::createFromFormat(Config::$formats->sortable_date, $value);
        if ($dt) {
            return date(Config::$formats->sortable_date, $service->timestamp) >= $dt->format(Config::$formats->sortable_date);
        }

        // if we get here $value was an invalid date format so return false
        return false;
    }
}
