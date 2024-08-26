<?php

namespace Obadiah\Pages\Prayer;

use DateTimeImmutable;
use Obadiah\Admin\Result;
use Obadiah\App;

App::check();

class Edit_Model
{
    /**
     * Create Edit model.
     *
     * @param Result|null $result       Operation result.
     * @param DateTimeImmutable $for    The first day of the month being edited.
     * @param array $days               The people assigned to each day of the month (excluding Sundays).
     * @param array $people             Hashes of the people who are already on this month's prayer calendar
     * @param int $per_day              The average number of people per day to fill the calendar evenly.
     * @return void
     */
    public function __construct(
        public readonly ?Result $result,
        public readonly DateTimeImmutable $for,
        public readonly array $days,
        public readonly array $people,
        public readonly int $per_day
    ) {
    }
}
