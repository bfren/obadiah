<?php

namespace Feeds\Config;

class Config
{
    /**
     * Root working directory.
     *
     * @var string
     */
    public static string $cwd;

    /**
     * Path to  data directory.
     *
     * @var string
     */
    public static string $dir_data;

    /**
     * Path to cache data directory.
     *
     * @var string
     */
    public static string $dir_cache;

    /**
     * Path to rota data directory.
     *
     * @var string
     */
    public static string $dir_rota;

    /**
     * Airtable config object.
     *
     * @var Config_Airtable
     */
    public static Config_Airtable $airtable;

    /**
     * Cache config object.
     *
     * @var Config_Cache
     */
    public static Config_Cache $cache;

    /**
     * Formats config object.
     *
     * @var Config_Formats
     */
    public static Config_Formats $formats;

    /**
     * Rota config object.
     *
     * @var Config_Rota
     */
    public static Config_Rota $rota;

    /**
     * Set standard config values, and read config YAML file into objects.
     *
     * @param string $cwd               Current working directory.
     * @return void
     */
    public static function load(string $cwd)
    {
        // standard config
        self::$cwd = $cwd;
        self::$dir_data = $cwd . "/data";
        self::$dir_cache = self::$dir_data . "/cache";
        self::$dir_rota = self::$dir_data . "/rota";

        // ensure data directories exist
        self::ensure_directory_exists(self::$dir_data);
        self::ensure_directory_exists(self::$dir_cache);
        self::ensure_directory_exists(self::$dir_rota);

        // read configuration file
        $config_file = "$cwd/config.yml";
        $config = yaml_parse_file($config_file);

        // create configuration objects
        self::$airtable = new Config_Airtable($config["airtable"]);
        self::$cache = new Config_Cache($config["cache"]);
        self::$formats = new Config_Formats($config["formats"]);
        self::$rota = new Config_Rota($config["rota"]);
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
