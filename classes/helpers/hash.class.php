<?php

namespace Feeds\Helpers;

use Feeds\App;
use Feeds\Prayer\Person;
use Feeds\Rota\Combined_Service;

App::check();

class Hash
{
    /**
     * Generate a hash of a person.
     *
     * @param Person $person            Person object.
     * @return string                   MD5 hash.
     */
    public static function person(Person $person): string
    {
        return md5($person->get_full_name());
    }

    /**
     * Generate a hash of a service.
     *
     * @param Combined_Service $service Service object.
     * @return string                   MD5 hash.
     */
    public static function service(Combined_Service $service): string
    {
        return md5(sprintf("%s%s", $service->start->format("c"), $service->name));
    }
}
