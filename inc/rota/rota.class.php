<?php

namespace ChurchSuiteFeeds\Rota;

class Rota {

    /**
     * All the services in this rota.
     *
     * @var Service[]
     */
    public array $services;

    /**
     * Construct using Rota::load().
     *
     * @return void
     */
    private function __construct() { }

    /**
     * Load all files from a rota data directory.
     *
     * @param string $path Rota data directory.
     * @return Rota
     */
    public static function load( $path )
    {
        // create rota
        $rota = new Rota();

        // get csv files from path
        $csv = glob( $path . "/*.csv" );

        // read each file
        foreach ($csv as $file) {

            // open the file for reading
            $f = fopen( $file, "r" );
            if ( $f === false ) {
                die( "Unable to open the file: $file." );
            }

            // read each line
            $include = false;
            $header_row = array();
            while ( ( $row = fgetcsv( $f ) ) !== false ) {

                // include the service
                if ( $include ) {
                    $rota->services[] = new Service( $header_row, $row );
                }

                // check whether to include the next row
                if ( $row[0] === "Date" ) {
                    $header_row = $row;
                    $include = true;
                }

            }
        }

        // return the rota object
        return $rota;
    }
}
