<?php

namespace Obadiah\Config;

use Obadiah\App;
use Obadiah\Helpers\Arr;

App::check();

class Config_Prayer extends Config_Section
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
     * The number of recent months to show by default.
     *
     * @var int
     */
    public readonly int $show_recent_months;

    /**
     * Whether or not to show the full last name for everyone, or an initial.
     *
     * @var bool
     */
    public readonly bool $show_last_name;

    /**
     * Get values from prayer configuration array.
     *
     * @param mixed[] $config           Prayer configuration array.
     * @return void
     */
    public function __construct(array $config)
    {
        $this->day_29 = Arr::get($config, "day_29", []);
        $this->day_30 = Arr::get($config, "day_30", []);
        $this->day_31 = Arr::get($config, "day_31", []);
        $this->show_last_name = Arr::get_boolean($config, "show_last_name", false);
        $this->show_recent_months = Arr::get_integer($config, "show_recent_months", 6);
    }

    public function as_array(): array
    {
        return [
            "day_29" => $this->day_29,
            "day_30" => $this->day_30,
            "day_31" => $this->day_31,
            "show_last_name" => $this->show_last_name,
            "show_recent_months" => $this->show_recent_months,
        ];
    }
}
