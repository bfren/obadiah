<?php

namespace Feeds\Rota;

use Feeds\Base;

class Rota
{
    /**
     * The services covered by this rota.
     *
     * @var Service[]
     */
    public array $services;

    /**
     * Construct using Rota::load().
     *
     * @return void
     */
    private function __construct()
    {
    }

    /**
     * Load all files from a rota data directory.
     *
     * @param Base $base                Base object.
     * @return Rota                     Rota object with services data loaded.
     */
    public static function load_csv(Base $base)
    {
        // create rota
        $rota = new Rota();

        // get csv files from path
        $csv = glob($base->dir_rota . "/*.csv");

        // read each file
        foreach ($csv as $file) {

            // open the file for reading
            $f = fopen($file, "r");
            if ($f === false) {
                die("Unable to open the file: $file.");
            }

            // read each line of the csv file
            $include = false;
            $header_row = array();

            while (($row = fgetcsv($f)) !== false) {
                // include the service
                if ($include) {
                    $rota->services[] = new Service($header_row, $row);
                }

                // check whether to include the next row
                if ($row[0] === "Date") {
                    $header_row = $row;
                    $include = true;
                }
            }
        }

        // return the rota object
        return $rota;
    }
}
