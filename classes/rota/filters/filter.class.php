<?php

namespace Feeds\Rota\Filters;

use Feeds\App;
use Feeds\Lectionary\Lectionary;
use Feeds\Rota\Service;

App::check();

interface Filter
{
    /**
     * Returns true if the provided service matches $value.
     *
     * @param Lectionary $lectionary    Lectionary object
     * @param Service $service          Service object.
     * @param string $value             Filter value (e.g. the date or a person's name).
     * @return bool                     True if the service matches $value.
     */
    public function apply(Lectionary $lectionary, Service $service, string $value) : bool;
}
