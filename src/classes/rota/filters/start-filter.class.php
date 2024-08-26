<?php

namespace Obadiah\Rota\Filters;

use Obadiah\App;
use Obadiah\Lectionary\Lectionary;
use Obadiah\Rota\Service;

App::check();

class Start_Filter implements Filter
{
    /**
     * Returns true if the service starts at the specified time.
     *
     * @param Lectionary $lectionary    Lectionary object
     * @param Service $service          Service object.
     * @param string $value             A time formatted HH:MM (24 hour).
     * @return bool                     True if the service is starts at the specified time.
     */
    public function apply(Lectionary $lectionary, Service $service, string $value): bool
    {
        // if no time value is set, include the service
        if (!$value) {
            return true;
        }

        // compare the time with the value
        return $service->start->format("H:i") == $value;
    }
}
