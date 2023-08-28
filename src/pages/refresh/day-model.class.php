<?php

namespace Feeds\Pages\Refresh;

use DateTimeImmutable;
use Feeds\App;
use Feeds\Bible\Bible_Plan;
use Feeds\Lectionary\Lectionary;
use Feeds\Prayer\Prayer_Calendar;

App::check();

class Day_Model
{
    /**
     * Create Day model.
     *
     * @param DateTimeImmutable $day            Formatted string to display the month being displayed.
     * @param Bible_Plan $bible_plan            Bible Plan (to save loading each time).
     * @param Lectionary $lectionary            Lectionary (to save loading each time).
     */
    public function __construct(
        public readonly DateTimeImmutable $day,
        public readonly Bible_Plan $bible_plan,
        public readonly Lectionary $lectionary,
    ) {
    }
}
