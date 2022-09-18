<?php

namespace Feeds\Refresh;

use DateTimeImmutable;
use Feeds\App;
use Feeds\Bible\Day as Readings;
use Feeds\Config\Config as C;
use Feeds\Helpers\Arr;
use Feeds\Prayer\Person;

App::check();

class Day
{
    /**
     * Create Day object.
     *
     * @param DateTimeImmutable $date   The date.
     * @param null|Readings $readings   Bible readings.
     * @param string[] $people          Array of people.
     * @return void
     */
    public function __construct(
        public readonly DateTimeImmutable $date,
        public readonly ?Readings $readings,
        public readonly array $people
    ) {
    }

    public function get_summary(): string
    {
        return sprintf("Refresh %s", $this->date->format(C::$formats->refresh_date));
    }

    public function get_description(): string
    {
        $description = array();

        // add readings
        if ($this->readings) {
            $description[] = "= Readings =";
            $description[] = sprintf("Psalms %s", $this->readings->ot_psalms);
            $description[] = $this->readings->ot_1;
            $description[] = $this->readings->ot_2;
            $description[] = $this->readings->nt_gospels;
            $description[] = $this->readings->nt_epistles;
            $description[] = "";
        }

        // add people
        if (!empty($this->people)) {
            $description[] = "= People =";
            $description[] = join("\\n", $this->people);
            $description[] = "";
        }

        // return description
        return join("\\n", $description);
    }
}
