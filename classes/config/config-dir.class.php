<?php

namespace Feeds\Config;

use Feeds\App;

App::check();

class Config_Dir
{
    /**
     * Path to data directory.
     *
     * @var string
     */
    public readonly string $data;

    /**
     * Path to cache data directory.
     *
     * @var string
     */
    public readonly string $cache;

    /**
     * Path to rota data directory.
     *
     * @var string
     */
    public readonly string $rota;

    /**
     * Path to prayer calendar data directory.
     *
     * @var string
     */
    public readonly string $prayer;

    /**
     * Ensure data directories exist.
     *
     * @param array $cwd                Current working directory.
     * @return void
     */
    public function __construct(public readonly string $cwd)
    {
        // build paths to data directories
        $this->data = sprintf("%s/data", $this->cwd);
        $this->cache = sprintf("%s/cache", $this->data);
        $this->rota = sprintf("%s/rota", $this->data);
        $this->prayer = sprintf("%s/prayer", $this->data);

        // ensure data directories exist
        self::ensure_directory_exists($this->data);
        self::ensure_directory_exists($this->cache);
        self::ensure_directory_exists($this->rota);
        self::ensure_directory_exists($this->prayer);
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
