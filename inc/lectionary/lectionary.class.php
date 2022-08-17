<?php

namespace Feeds\Lectionary;

class Lectionary
{
    /**
     * The days covered by this lectionary, sorted by date.
     *
     * @var array
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
     * @param string $path              Lectionary data directory.
     * @return Lectionary               Lectionary object with readings and titles data loaded.
     */
    public static function load_csv(string $path)
    {
    }
}
