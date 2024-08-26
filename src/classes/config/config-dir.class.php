<?php

namespace Obadiah\Config;

use Obadiah\App;
use SplFileInfo;

App::check();

class Config_Dir
{
    /**
     * Path to Bible Plan data directory.
     *
     * @var string
     */
    public readonly string $bible;

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
     * Page to pages directory.
     *
     * @var string
     */
    public readonly string $pages;

    /**
     * Path to prayer calendar data directory.
     *
     * @var string
     */
    public readonly string $prayer;

    /**
     * Ensure data directories exist.
     *
     * @param string $cwd               Current working directory.
     * @param string $data_dir          Absolute path to data directory.
     * @return void
     */
    public function __construct(public readonly string $cwd, public readonly string $data_dir)
    {
        // build paths to data directories
        $this->bible = sprintf("%s/bible", $this->data_dir);
        $this->cache = sprintf("%s/cache", $this->data_dir);
        $this->rota = sprintf("%s/rota", $this->data_dir);
        $this->prayer = sprintf("%s/prayer", $this->data_dir);

        // ensure data directories exist
        self::ensure_directory_exists($this->bible);
        self::ensure_directory_exists($this->cache);
        self::ensure_directory_exists($this->rota);
        self::ensure_directory_exists($this->prayer);

        // additional directory paths
        $this->pages = sprintf("%s/pages", $this->cwd);
    }

    /**
     * Ensure a directory exists.
     *
     * @param string $path              Directory path to create if it does not already exist.
     * @return void
     */
    private static function ensure_directory_exists(string $path): void
    {
        $dir = new SplFileInfo($path);
        if (!$dir->isDir()) {
            mkdir($path);
        }
    }
}
