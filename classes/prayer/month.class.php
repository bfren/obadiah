<?php

namespace Feeds\Prayer;

use Feeds\Admin\Result;
use Feeds\App;
use Feeds\Config\Config as C;
use Throwable;

App::check();

class Month
{
    /**
     * The maximum number of days for assigning people to in any given month.
     */
    public const MAX_DAYS = 24;

    /**
     * Create Month object.
     *
     * @param string $id                Month ID, format YYYY-MM.
     * @param string[][] $days          The people assigned to each day, stored by hash.
     *      { 'YYYY-MM-DD' => string[] }
     * @param string[] $people          Everyone included in this month's prayer calendar, stored by hash.
     * @return void
     */
    public function __construct(
        public readonly string $id,
        public readonly array $days,
        public readonly array $people
    ) {
    }

    /**
     * Create a Month object from $data and save it to the data store.
     *
     * @param mixed $data               Data posted from Prayer Calendar builder.
     * @return Result
     */
    public static function save($data): Result
    {
        try {
            // get data
            $id = $data->id;
            $days = array();
            foreach ($data->days as $day) {
                $days[$day->date] = $day->people;
            }
            $people = $data->people;
        } catch (Throwable $th) {
            return Result::failure("Unable to read month data.");
        }

        // create month
        $month = new Month($id, $days, $people);

        // get path to data file
        $path = sprintf("%s/%s.month", C::$dir->prayer, $month->id);

        // serialise and save to file
        $data = serialize($month);
        try {
            file_put_contents($path, $data);
        } catch (Throwable $th) {
            return Result::failure("Unable to save month data.");
        }

        // return success result
        return Result::success("Month saved");
    }

    /**
     * Load the days for a specific month from a data store, if it exists.
     *
     * @param string $id                Month ID, format YYYY-MM.
     * @return Month                    Month object containing deserialised days (if store file exists).
     */
    public static function load(string $id): Month
    {
        // get path to data file
        $path = sprintf("%s/%s.month", C::$dir->prayer, $id);

        // if the file exists, read and deserialise
        if ($data = file_get_contents($path)) {
            return unserialize($data);
        }

        // return empty Month object
        return new Month($id, array(), array());
    }

    /**
     * Return most recent Month, or a blank Month object.
     *
     * @return Month                    Most recent (or blank) month.
     */
    public static function get_most_recent(): Month
    {
        // get months
        $months = Prayer_Calendar::get_months();

        // create Month object from the most recent
        if($most_recent = end($months)) {
            return self::load($most_recent);
        }

        // return blank month
        return new Month("", array(), array());
    }
}
