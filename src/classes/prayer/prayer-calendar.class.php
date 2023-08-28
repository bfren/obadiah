<?php

namespace Feeds\Prayer;

use DateTimeImmutable;
use Feeds\App;
use Feeds\Cache\Cache;
use Feeds\Config\Config as C;
use Feeds\Helpers\Arr;

App::check();

class Prayer_Calendar
{
    /**
     * Get matching people objects from an array of hashes.
     *
     * @param string[] $hashes          Array of hashes.
     * @return Person[]                 Array of matching Person objects.
     */
    public function get_people(?array $hashes = null): array
    {
        // get people from cache and return if there are no hashes to match
        $all_people = Cache::get_people();
        if (!$hashes) {
            return $all_people;
        }

        // get matching people
        $matching_people = Arr::map($hashes, fn (string $hash) => Arr::get($all_people, $hash, array()));

        // sort and return
        self::sort_people($matching_people);
        return $matching_people;
    }

    /**
     * Get the names of people on a particular day from the prayer calendar.
     *
     * @param DateTimeImmutable $dt     Date.
     * @return string[]                 Array of people.
     */
    public function get_day(DateTimeImmutable $dt): array
    {
        // get month
        $id = $dt->format(C::$formats->prayer_month_id);
        $month = Month::load($id);

        // return empty array if the month does not exist
        if ($month === null) {
            return array();
        }

        // if we are at the end of the month return the configured additional people
        $day = (int)$dt->format("j");
        if (in_array($day, array(29, 30, 31))) {
            $day = sprintf("day_%s", $day);
            return C::$prayer->$day;
        }

        // get the people hashes for the day
        $hashes = Arr::get($month->days, $dt->format(C::$formats->sortable_date), array());

        // return people's names
        return Arr::map($this->get_people($hashes), fn (Person $p) => $p->get_full_name(C::$prayer->show_last_name));
    }

    /**
     * Sort an array of Person objects by last name and then first name.
     *
     * @param Person[] $people          Array of Person objects.
     * @return void
     */
    public static function sort_people(array &$people): void
    {
        // use custom sort - maintaining array keys
        uasort($people, function (Person $a, Person $b) {
            // if last names match, compare first names
            if ($a->last_name == $b->last_name) {
                return $a->first_name <=> $b->first_name;
            }

            // compare last names
            return $a->last_name <=> $b->last_name;
        });
    }

    /**
     * Get filenames of months that have been created for the prayer calendar.
     *
     * @return string[]                 Array of months.
     */
    public static function get_months(): array
    {
        // get saved month files
        $files = glob(sprintf("%s/*.month", C::$dir->prayer));
        sort($files);

        // return each month without the '.month' extension
        return str_replace(array(C::$dir->prayer, "/", ".month"), "", $files);
    }
}
