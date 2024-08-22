<?php

namespace Obadiah\Rota\Filters;

use Obadiah\App;
use Obadiah\Lectionary\Lectionary;
use Obadiah\Rota\Service;

App::check();

class Series_Filter implements Filter
{
    /**
     * Returns true if the teaching matches the specified series.
     *
     * @param Lectionary $lectionary    Lectionary object
     * @param Service $service          Service object.
     * @param string $value             Series title.
     * @return bool                     True if the service is starts at the specified time.
     */
    public function apply(Lectionary $lectionary, Service $service, string $value): bool
    {
        // if no time value is set, include the service
        if (!$value) {
            return true;
        }

        // get the lectionary service
        $l_service = $lectionary->get_service($service->start);
        if (!$l_service) {
            return false;
        }

        // compare the series title with the value
        return $l_service->series == $value;
    }
}
