<?php

namespace Feeds\Refresh;

use DateTimeImmutable;
use Feeds\App;
use Feeds\Bible\Day as Readings;
use Feeds\Config\Config as C;
use Feeds\Prayer\Person;

App::check();

class Day
{
    /**
     * Create Day object.
     *
     * @param DateTimeImmutable $date   The date.
     * @param Person[]|string[] $people Array of people.
     * @param null|Readings $readings   Bible readings.
     * @return void
     */
    public function __construct(
        public readonly DateTimeImmutable $date,
        public readonly array $people,
        public readonly ?Readings $readings
    ) {
    }

    /**
     * Build event summary.
     *
     * @return string                   Event summary.
     */
    public function get_summary(): string
    {
        return sprintf("Refresh %s", $this->date->format(C::$formats->refresh_date));
    }

    /**
     * Build event description.
     *
     * @param string $separator         Line separator.
     * @return string                   Event description.
     */
    public function get_description(string $separator = "\\n"): string
    {
        $description = array();

        // add people
        if (!empty($this->people)) {
            $description[] = "= People =";
            $description[] = join($separator, $this->people);
            $description[] = "";
        }

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

        // return description
        return join($separator, $description);
    }
}
