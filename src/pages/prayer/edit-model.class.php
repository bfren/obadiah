<?php

namespace Feeds\Pages\Prayer;

use DateTimeImmutable;
use Feeds\Admin\Result;
use Feeds\App;
use Feeds\Prayer\Prayer_Calendar;

App::check();

class Edit_Model
{
    public function __construct(
        public readonly ?Result $result,
        public readonly Prayer_Calendar $prayer,
        public readonly DateTimeImmutable $for,
        public readonly array $days,
        public readonly array $people,
        public readonly int $per_day
    )
    {

    }
}
