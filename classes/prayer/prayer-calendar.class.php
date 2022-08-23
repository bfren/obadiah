<?php

namespace Feeds\Prayer;

use Feeds\App;
use Feeds\Config\Config as C;

App::check();

class Prayer_Calendar
{
    /**
     * The array of people in the Prayer Calendar.
     *
     * @var Person[]
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
        $adults = $this->get_people("adults", false);
        $children = $this->get_people("children", true);

        // merge and sort array by last name and then first name
        $people = array_merge($adults, $children);
        usort($people, fn(Person $a, Person $b) => $a->last_name != $b->last_name ? strcmp($a->last_name, $b->last_name) : strcmp($a->first_name, $b->first_name));

        // store people
        $this->people = $people;
    }

    /**
     * Read people's names from the specified CSV file.
     *
     * @param string $filename          The name of the file (without path or .csv extension).
     * @param bool $is_child            Whether or not the person is a child.
     * @return Person[]
     */
    private function get_people(string $filename, bool $is_child):array
    {
        // get csv files from path
        $file = sprintf("%s/%s.csv", C::$dir->prayer, $filename);

        // read file into array
        $people = array();

        // open the file for reading
        $f = fopen($file, "r");
        if ($f === false) {
            die(sprintf("Unable to open the file: %s.", $file));
        }

        // read each line of the csv file
        $first = true;
        while (($row = fgetcsv($f)) !== false) {
            // skip the first row
            if ($first) {
                $first = false;
                continue;
            }

            // add the person
            $people[] = new Person($row[0], $row[1], $is_child);
        }

        // return the array of people
        return $people;
    }
}
