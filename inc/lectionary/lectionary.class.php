<?php

namespace Feeds\Lectionary;

use Feeds\Airtable\Airtable;
use Feeds\Base;

class Lectionary
{
    /**
     * The days covered by this lectionary, sorted by date.
     *
     * @var Day[]
     */
    public array $days;

    /**
     * Construct using Lectionary::load().
     *
     * @return void
     */
    private function __construct()
    {
    }

    /**
     * Load all files from a lectionary data directory.
     *
     * @param Base $base                Base object.
     * @return Lectionary               Lectionary object with readings and titles data loaded.
     */
    public static function load_csv(Base $base)
    {
    }

    /**
     * Load lectionary from Airtable.
     *
     * @param Base $base                Base object.
     * @return Lectionary               Lectionary object with readings and titles data loaded.
     */
    public static function load_airtable(Base $base)
    {
        // create Airtable loaders
        $days = new Airtable($base, "Day");
        $services = new Airtable($base, "Service");

        // get days
        $days_records = $days->make_request(array("view" => "Feed", "fields" => array("Date", "Name")));
        print_r($days_records);
    }
}
