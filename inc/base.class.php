<?php

namespace Feeds;

class Base
{

    /**
     * Path to lectionary data directory.
     *
     * @var string
     */
    public string $dir_lectionary;

    /**
     * Path to rota data directory.
     *
     * @var string
     */
    public string $dir_rota;

    /**
     * Construct using Base::preflight.
     *
     * @return void
     */
    private function __construct()
    {
    }

    /**
     * Run preflight checks.
     *
     * @param string $cwd               The current working directory.
     * @return Base
     */
    public static function preflight(string $cwd)
    {
        // create base
        $base = new Base();

        // build paths to data directories
        $dir_data = "$cwd/data";
        $base->dir_lectionary = "$dir_data/lectionary";
        $base->dir_rota = "$dir_data/rota";

        // ensure data directories exist
        self::ensure_directory_exists($dir_data);
        self::ensure_directory_exists($base->dir_lectionary);
        self::ensure_directory_exists($base->dir_rota);

        // return base
        return $base;
    }

    /**
     * Ensure a directory exists.
     *
     * @param string $path              Directory path to create if it does not already exist.
     * @return void
     */
    private static function ensure_directory_exists(string $path)
    {
        if (!file_exists($path)) {
            mkdir($path);
        }
    }
}
