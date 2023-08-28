<?php

namespace Feeds\Config;

use Feeds\App;
use Feeds\Helpers\Arr;

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
     * HTML to display on the footer of page 1 (left-hand side).
     *
     * @var string
     */
    public readonly string $footer_page_1_left;

    /**
     * HTML to display on the footer of page 1 (right-hand side).
     *
     * @var string
     */
    public readonly string $footer_page_1_right;

    /**
     * HTML to display on the footer of page 2 (left-hand side).
     *
     * @var string
     */
    public readonly string $footer_page_2_left;

    /**
     * HTML to display on the footer of page 2 (right-hand side).
     *
     * @var string
     */
    public readonly string $footer_page_2_right;

    /**
     * Whether or not to show the full last name for everyone, or an initial.
     *
     * @var bool
     */
    public readonly bool $show_last_name;

    /**
     * Get values from events configuration array.
     *
     * @param array $config             Events configuration array.
     * @return void
     */
    public function __construct(array $config)
    {
        $this->day_29 = Arr::get($config, "day_29", array());
        $this->day_30 = Arr::get($config, "day_30", array());
        $this->day_31 = Arr::get($config, "day_31", array());
        $this->footer_page_1_left = Arr::get($config, "footer_page_1_left", "");
        $this->footer_page_1_right = Arr::get($config, "footer_page_1_right", "");
        $this->footer_page_2_left = Arr::get($config, "footer_page_2_left", "");
        $this->footer_page_2_right = Arr::get($config, "footer_page_2_right", "");
        $this->show_last_name = Arr::get_boolean($config, "show_last_name", false);
    }
}
