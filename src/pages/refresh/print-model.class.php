<?php

namespace Feeds\Pages\Refresh;

use DateTimeImmutable;
use Feeds\App;

App::check();

class Print_Model
{
    /**
     * Create Print model.
     *
     * @param string $month                 Formatted string to display the month being displayed.
     * @param DateTimeImmutable $first_day  The first day to display.
     * @param DateTimeImmutable $last_day   The last day to display.
     */
    public function __construct(
        public readonly string $month,
        public readonly DateTimeImmutable $first_day,
        public readonly DateTimeImmutable $last_day,
    ) {
    }
}
