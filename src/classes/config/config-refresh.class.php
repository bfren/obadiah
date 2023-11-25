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
     * Get values from refresh configuration array.
     *
     * @param array $config             Refresh configuration array.
     * @return void
     */
    public function __construct(array $config)
    {
        $this->days_before = Arr::get_integer($config, "days_before", 7);
        $this->days_after = Arr::get_integer($config, "days_after", 2);
        $this->footer_page_1_left = Arr::get($config, "footer_page_1_left", "");
        $this->footer_page_1_right = Arr::get($config, "footer_page_1_right", "");
        $this->footer_page_2_left = Arr::get($config, "footer_page_2_left", "");
        $this->footer_page_2_right = Arr::get($config, "footer_page_2_right", "");
    }
}
