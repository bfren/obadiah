<?php

namespace Feeds\Rota\Filters;

use Feeds\Rota\Service;

class Person implements Filter
{
    /**
     * Returns true if the person ($value) is doing one of the roles.
     *
     * @param Service $service          Service object.
     * @param string $value             A person's name.
     * @return bool                     True if person ($value) is doing one of the roles in the service.
     */
    public function apply(Service $service, string $value) : bool
    {
        // if no person value is set, include the service
        if (!$value) {
            return true;
        }

        // loop through each role - the first time the person is matched, return true
        foreach ($service->roles as $people) {
            if (in_array($value, $people)) {
                return true;
            }
        }

        // if we get here the person has not matched any of the roles so return false
        return false;
    }
}
