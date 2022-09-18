<?php

namespace Feeds\Prayer;

use DateTimeImmutable;
use Feeds\App;
use Feeds\Config\Config as C;
use Feeds\Helpers\Arr;
use Feeds\Helpers\Hash;
use SplFileInfo;
use Throwable;

App::check();

class Prayer_Calendar
{
    /**
     * The array of people in the Prayer Calendar, stored by hash.
     *
     * @var Person[]
     *      array(string $hash => Person $person)
     */
    public readonly array $people;

    /**
     * Get people from CSV files.
     *
     * @return void
     */
    public function __construct()
    {
        // get adults and children
        $adults = $this->read_file("adults", false);
        $children = $this->read_file("children", true);

        // merge and sort array by last name and then first name
        $people = array_merge($adults, $children);
        self::sort_people($people);

        // store people
        $this->people = $people;
    }

    /**
     * Get matching people objects from an array of hashes.
     *
     * @param string[] $hashes          Person hash.
     * @return Person[]                 Array of person objects.
     */
    public function get_people(array $hashes): array
    {
        // get matching people
        $people = Arr::map($hashes, fn (string $hash) => Arr::get($this->people, $hash, array()));

        // sort and return
        self::sort_people($people);
        return $people;
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
                return strcmp($a->first_name, $b->first_name);
            }

            // compare last names
            return strcmp($a->last_name, $b->last_name);
        });
    }

    /**
     * Read people's names from the specified CSV file.
     *
     * @param string $filename          The name of the file (without path or .csv extension).
     * @param bool $is_child            Whether or not the person is a child.
     * @return Person[]
     */
    private function read_file(string $filename, bool $is_child): array
    {
        // get csv files from path
        $path = sprintf("%s/%s.csv", C::$dir->prayer, $filename);
        $file_info = new SplFileInfo($path);
        if (!$file_info->isFile()) {
            return array();
        }

        // read file into array
        $people = array();

        // open the file for reading
        try {
            $file_obj = $file_info->openFile("r");
        } catch (Throwable $th) {
            App::die("Unable to open the file: %s.", $file_info);
        }

        // read each line of the csv file
        $first = true;
        while (!$file_obj->eof()) {
            // get row
            $row = $file_obj->fgetcsv();

            // skip the first row
            if ($first) {
                $first = false;
                continue;
            }

            // skip empty rows
            if (count($row) != 2) {
                continue;
            }

            // add the person using a hash of the name as array key
            $person = new Person($row[0], $row[1], $is_child);
            $people[Hash::person($person)] = $person;
        }

        // return the array of people
        return $people;
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
