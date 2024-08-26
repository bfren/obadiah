<?php

namespace Obadiah\Refresh;

use DateTimeImmutable;
use Obadiah\App;
use Obadiah\Bible\Day as Readings;
use Obadiah\Cache\Cache;
use Obadiah\Config\Config as C;
use Obadiah\Helpers\Arr;
use Obadiah\Prayer\Person;

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
        $description = [];

        // add readings
        if ($this->readings) {
            $description[] = "= Readings =";
            $description[] = sprintf("1: Psalms %s", $this->readings->ot_psalms);
            $description[] = sprintf("2: %s", $this->readings->ot_1);
            $description[] = sprintf("3: %s", $this->readings->ot_2);
            $description[] = sprintf("4: %s", $this->readings->nt_gospels);
            $description[] = sprintf("5: %s", $this->readings->nt_epistles);
            $description[] = "";
        }

        // add people
        if (!empty($this->people)) {
            $description[] = "= People =";
            $people = array_values($this->people);
            if ($people[0] instanceof Person) {
                $description[] = join($separator, Arr::map($people, fn (Person $person) => $person->get_full_name(C::$prayer->show_last_name)));
            } else {
                $description[] = join($separator, $people);
            }
            $description[] = "";
        }

        // add collect
        if (($collect = Cache::get_lectionary()->get_collect($this->date)) !== null) {
            $description[] = "= Collect =";
            $description[] = join($separator, preg_split("/\n/", $collect));
            $description[] = "";
        }

        // return description
        return join($separator, $description);
    }
}
