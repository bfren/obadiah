<?php

namespace Obadiah\Rota\Filters;

use DateTimeImmutable;
use Obadiah\App;
use Obadiah\Config\Config as C;
use Obadiah\Lectionary\Lectionary;
use Obadiah\Rota\Service;
use Throwable;

App::check();

class Before_Filter implements Filter
{
    /**
     * Returns true if the service is before the specified date.
     *
     * @param Lectionary $lectionary    Lectionary object
     * @param Service $service          Service object.
     * @param string $value             A date formatted YYYY-MM-DD.
     * @return bool                     True if the service is before the specified date.
     */
    public function apply(Lectionary $lectionary, Service $service, string $value): bool
    {
        // if no date value is set, include the service
        if (!$value) {
            return true;
        }

        // convert the date to a timestamp
        try {
            $dt = new DateTimeImmutable($value, C::$events->timezone);
            return $service->start->format(C::$formats->sortable_date) <= $dt->format(C::$formats->sortable_date);
        } catch (Throwable $th) {
            _l_throwable($th);
            return false;
        }
    }
}
