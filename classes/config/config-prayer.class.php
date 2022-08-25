<?php

namespace Feeds\Config;

use DateTimeZone;
use Feeds\App;

App::check();

class Config_Prayer
{
    /**
     * Who / what to pray for on the 29th day of the month.
     *
     * @var string[]
     */
    public readonly array $day_29;

    /**
     * Who / what to pray for on the 30th day of the month.
     *
     * @var string[]
     */
    public readonly array $day_30;

    /**
     * Who / what to pray for on the 31st day of the month.
     *
     * @var string[]
     */
    public readonly array $day_31;

    /**
     * Whether or not to show the full surname for everyone, or an initial.
     *
     * @var bool
     */
    public readonly bool $show_surname;

    /**
     * Get values from events configuration array.
     *
     * @param array $config             Events configuration array.
     * @return void
     */
    public function __construct(array $config)
    {
        $this->day_29 = $config["day_29"];
        $this->day_30 = $config["day_30"];
        $this->day_31 = $config["day_31"];
        $this->show_surname = $config["show_surname"];
    }
}
