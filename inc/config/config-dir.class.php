<?php

namespace Feeds\Config;

class Config_Dir
{
    /**
     * Current working directory.
     *
     * @var string
     */
    public string $cwd;

    /**
     * Path to data directory.
     *
     * @var string
     */
    public string $data;

    /**
     * Path to cache data directory.
     *
     * @var string
     */
    public string $cache;

    /**
     * Path to rota data directory.
     *
     * @var string
     */
    public string $rota;

    /**
     * Ensure data directories exist.
     *
     * @param array $cwd                Current working directory.
     * @return void
     */
    public function __construct(string $cwd)
    {
        // store working directory
        $this->cwd = $cwd;

        // build paths to data directories
        $this->data = "$cwd/data";
        $this->cache = "$this->data/cache";
        $this->rota = "$this->data/rota";

        // ensure data directories exist
        self::ensure_directory_exists($this->data);
        self::ensure_directory_exists($this->cache);
        self::ensure_directory_exists($this->rota);
    }

    /**
     * Ensure a directory exists.
     *
     * @param string $path              Directory path to create if it does not already exist.
     * @return void
     */
    private static function ensure_directory_exists(string $path): void
    {
        if (!file_exists($path)) {
            mkdir($path);
        }
    }
}
