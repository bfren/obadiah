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
     * Construct and run preflight checks.
     *
     * @param string $cwd               The current working directory.
     */
    public function __construct(string $cwd)
    {
        // build paths to data directories
        $dir_data = "$cwd/data";
        $this->dir_cache = "$dir_data/cache";
        $this->dir_rota = "$dir_data/rota";

        // ensure data directories exist
        self::ensure_directory_exists($dir_data);
        self::ensure_directory_exists($this->dir_cache);
        self::ensure_directory_exists($this->dir_rota);

        // read Airtable API key
        $file_airtable_api_key = "$dir_data/airtable_api_key.txt";
        if (file_exists($file_airtable_api_key)) {
            $this->airtable_api_key = trim(file_get_contents($file_airtable_api_key));
        }
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
