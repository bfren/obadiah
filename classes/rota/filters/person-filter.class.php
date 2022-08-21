<?php

namespace Feeds\Rota\Filters;

use Feeds\App;
use Feeds\Lectionary\Lectionary;
use Feeds\Rota\Service;

App::check();

class Person_Filter implements Filter
{
    /**
     * Returns true if the person ($value) is doing one of the roles.
     *
     * @param Lectionary $lectionary    Lectionary object
     * @param Service $service          Service object.
     * @param string $value             A person's name.
     * @return bool                     True if person ($value) is doing one of the roles in the service.
     */
    public function apply(Lectionary $lectionary, Service $service, string $value) : bool
    {
        // if no person value is set, include the service
        if (!$value) {
            return true;
        }

        // loop through each role - the first time the person is matched, return true
        foreach ($service->roles as $people) {
            foreach ($people as $person) {
                if (str_starts_with($person, $value)) {
                    return true;
                }
            }
        }

        // if we get here the person has not matched any of the roles so return false
        return false;
    }
}
