<?php

namespace Feeds\Rota;

use DateTime;

class Combined_Day
{
    /**
     * Service start time (e.g. 10:30).
     *
     * @var DateTime
     */
    public DateTime $dt;

    /**
     * Service description (e.g. 'Morning Prayer').
     *
     * @var null|string
     */
    public ?string $name;

    /**
     * Optional series title.
     *
     * @var Combined_Service[]
     */
    public array $services;
}
