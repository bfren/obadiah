<?php

namespace Feeds;

class Base
{
    /**
     * Path to cache data directory.
     *
     * @var string
     */
    public string $dir_cache;

    /**
     * Path to rota data directory.
     *
     * @var string
     */
    public string $dir_rota;

    /**
     * Airtable
     *
     * @var string
     */
    public string $airtable_api_key;

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
        $base->dir_cache = "$dir_data/cache";
        $base->dir_rota = "$dir_data/rota";

        // ensure data directories exist
        self::ensure_directory_exists($dir_data);
        self::ensure_directory_exists($base->dir_cache);
        self::ensure_directory_exists($base->dir_rota);

        // read Airtable API key
        $file_airtable_api_key = "$dir_data/airtable_api_key.txt";
        if (file_exists($file_airtable_api_key)) {
            $base->airtable_api_key = trim(file_get_contents($file_airtable_api_key));
        }

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
