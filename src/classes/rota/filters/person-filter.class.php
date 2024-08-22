<?php

namespace Obadiah\Rota\Filters;

use Obadiah\App;
use Obadiah\Lectionary\Lectionary;
use Obadiah\Rota\Service;

App::check();

class Person_Filter implements Filter
{
    /**
     * Returns true if the person ($value) is doing one of the ministries.
     *
     * @param Lectionary $lectionary    Lectionary object
     * @param Service $service          Service object.
     * @param string $value             A person's name.
     * @return bool                     True if person ($value) is doing one of the ministries in the service.
     */
    public function apply(Lectionary $lectionary, Service $service, string $value) : bool
    {
        // if no person value is set, include the service
        if (!$value) {
            return true;
        }

        // loop through each ministry - the first time the person is matched, return true
        foreach ($service->ministries as $service_ministry) {
            foreach ($service_ministry->people as $person) {
                if (str_starts_with($person, $value)) {
                    return true;
                }
            }
        }

        // if we get here the person has not matched any of the ministries so return false
        return false;
    }
}
