<?php

namespace Obadiah\Rota\Filters;

use Obadiah\App;
use Obadiah\Lectionary\Lectionary;
use Obadiah\Rota\Service;

App::check();

class Day_Filter implements Filter
{
    /**
     * Returns true if the service is on the specified day of the week.
     *
     * @param Lectionary $lectionary    Lectionary object
     * @param Service $service          Service object.
     * @param string $value             Day of the week number (Sunday is 1).
     * @return bool                     True if the service is on the specified day of the week.
     */
    public function apply(Lectionary $lectionary, Service $service, string $value): bool
    {
        // if no time value is set, include the service
        if (!$value) {
            return true;
        }

        // compare the day of the week with the value
        return $service->start->format("N") == $value;
    }
}
