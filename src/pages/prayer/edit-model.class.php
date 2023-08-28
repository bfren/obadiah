<?php

namespace Feeds\Pages\Prayer;

use DateTimeImmutable;
use Feeds\Admin\Result;
use Feeds\App;
use Feeds\Prayer\Prayer_Calendar;

App::check();

class Edit_Model
{
    /**
     * Create Edit model.
     *
     * @param null|Result $result       Operation result.
     * @param Prayer_Calendar $prayer   Prayer Calendar.
     * @param DateTimeImmutable $for    The first day of the month being edited.
     * @param array $days               The people assigned to each day of the month (excluding Sundays).
     * @param array $people             Hashes of the people who are already on this month's prayer calendar
     * @param int $per_day              The average number of people per day to fill the calendar evenly.
     * @return void
     */
    public function __construct(
        public readonly ?Result $result,
        public readonly Prayer_Calendar $prayer,
        public readonly DateTimeImmutable $for,
        public readonly array $days,
        public readonly array $people,
        public readonly int $per_day
    ) {
    }
}
