<?php

namespace Obadiah\Pages\Refresh;

use DateTimeImmutable;
use Obadiah\App;
use Obadiah\Bible\Bible_Plan;
use Obadiah\Lectionary\Lectionary;

App::check();

class Day_Model
{
    /**
     * Create Day model.
     *
     * @param DateTimeImmutable $day            The day being displayed.
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
