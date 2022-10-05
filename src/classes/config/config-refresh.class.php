<?php

namespace Feeds\Config;

use Feeds\App;
use Feeds\Helpers\Arr;

App::check();

class Config_Refresh
{
    /**
     * The number of days before today to include in the Refresh calendar feed.
     *
     * @var int
     */
    public readonly int $days_before;

    /**
     * The number of days after today to include in the Refresh calendar feed.
     *
     * @var int
     */
    public readonly int $days_after;

    /**
     * Get values from refresh configuration array.
     *
     * @param array $config             refresh configuration array.
     * @return void
     */
    public function __construct(array $config)
    {
        $this->days_before = Arr::get($config, "days_before");
        $this->days_after = Arr::get($config, "days_after");
    }
}
