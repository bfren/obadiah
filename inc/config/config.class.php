<?php

namespace Feeds\Config;

class Config
{
    /**
     * Request URI path.
     *
     * @var string
     */
    public static string $uri;

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
     * Dir config object.
     *
     * @var Config_Dir
     */
    public static Config_Dir $dir;

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
    public static function load(string $cwd): void
    {
        // set Request URI
        self::$uri = $_SERVER["REQUEST_URI"];

        // read configuration file
        $config_file = "$cwd/config.yml";
        $config = yaml_parse_file($config_file);

        // create configuration objects
        self::$airtable = new Config_Airtable($config["airtable"]);
        self::$cache = new Config_Cache($config["cache"]);
        self::$dir = new Config_Dir($cwd);
        self::$formats = new Config_Formats($config["formats"]);
        self::$rota = new Config_Rota($config["rota"]);
    }
}
