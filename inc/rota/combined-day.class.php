<?php

namespace Feeds\Rota;

use DateTime;

class Combined_Day
{
    /**
     * Date.
     *
     * @var DateTime
     */
    public DateTime $dt;

    /**
     * Lectionary name.
     *
     * @var null|string
     */
    public ?string $name;

    /**
     * Services on this day.
     *
     * @var Combined_Service[]
     */
    public array $services = array();
}
