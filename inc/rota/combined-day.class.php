<?php

namespace Feeds\Rota;

use DateTimeImmutable;

class Combined_Day
{
    /**
     * Date.
     *
     * @var DateTimeImmutable
     */
    public DateTimeImmutable $dt;

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
