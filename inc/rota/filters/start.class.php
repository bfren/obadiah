<?php

namespace Feeds\Rota\Filters;

use Feeds\Rota\Service;

defined("IDX") || die("Nice try.");

class Start implements Filter
{
    /**
     * Returns true if the service starts at the specified time.
     *
     * @param Service $service          Service object.
     * @param string $value             A time formatted HH:MM (24 hour).
     * @return bool                     True if the service is starts at the specified time.
     */
    public function apply(Service $service, string $value): bool
    {
        // if no time value is set, include the service
        if (!$value) {
            return true;
        }

        // compare the time with the value
        return $service->dt->format("H:i") == $value;
    }
}
