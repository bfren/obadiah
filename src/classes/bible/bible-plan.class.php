<?php

namespace Feeds\Bible;

use DateTimeImmutable;
use Feeds\Admin\Bible_File;
use Feeds\App;
use Feeds\Config\Config as C;
use SplFileInfo;
use Throwable;

App::check();

class Bible_Plan
{
    /**
     * Holds each day of the Bible reading plan.
     *
     * @var Day[]
     */
    public readonly array $days;

    /**
     * Read Bible reading plan from text file and store in $days array.
     *
     * @return void
     */
    public function __construct()
    {
        // get path to Bible plan file
        $path = sprintf("%s/%s.txt", C::$dir->bible, Bible_File::NAME);
        $file_info = new SplFileInfo($path);
        if (!$file_info->isFile()) {
            $this->days = array();
            return;
        }

        // read file contents
        try {
            $file_obj = $file_info->openFile("r");
        } catch (Throwable $th) {
            App::die("Unable to read the file: %s.", $file_info->getRealPath());
        }

        // build array of days
        $days = array();
        $trim = fn ($input) => trim(str_replace("\"", "", $input));

        while (!$file_obj->eof()) {
            // read the next line
            $line = $file_obj->fgets();

            // split line by tab
            $values = explode("\t", $line);
            if (count($values) != 6) {
                continue;
            }

            // read values
            $days[$values[0]] = new Day(
                ot_psalms: $trim($values[1]),
                ot_1: $trim($values[2]),
                ot_2: $trim($values[3]),
                nt_gospels: $trim($values[4]),
                nt_epistles: $trim($values[5])
            );
        }

        // store days
        $this->days = $days;
    }

    /**
     * Get the Bible reading plan day for the specified date.
     *
     * @param DateTimeImmutable $dt     Date to retrieve Bible readings for.
     * @return null|Day                 Day object (or null if $dt is a Sunday or towards the end of the year).
     */
    public function get_day(DateTimeImmutable $dt): ?Day
    {
        // if the day is a Sunday return null
        if ($dt->format("N") == 7) {
            return null;
        }

        // get first day of year
        $first_day = $dt->setDate($dt->format("Y"), 1, 1);

        // get number of days since first day of year
        $days = $dt->diff($first_day, true)->days + 1;

        // get week number for this date - starts with Monday so subtract 1 to get the number of Sundays to this point
        $week = (int)$dt->format("W");
        $sundays = $week > $days ? 0 : $week;

        // get the plan day number:
        // we skip Sundays so subtract the number of weeks from the number of days
        // then we add one because the index is 1-based
        $day = $days - $sundays;

        // return the day from the days array
        if ($day > count($this->days)) {
            return null;
        }

        return $this->days[$day];
    }
}
