<?php

namespace Feeds\Config;

use Feeds\App;

App::check();

class Config
{
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
     * Events config object.
     *
     * @var Config_Events
     */
    public static Config_Events $events;

    /**
     * Formats config object.
     *
     * @var Config_Formats
     */
    public static Config_Formats $formats;

    /**
     * General config object.
     *
     * @var Config_General
     */
    public static Config_General $general;

    /**
     * Login config object.
     *
     * @var Config_Login
     */
    public static Config_Login $login;

    /**
     * Prayer config object.
     *
     * @var Config_Prayer
     */
    public static Config_Prayer $prayer;

    /**
     * Rota config object.
     *
     * @var Config_Rota
     */
    public static Config_Rota $rota;

    /**
     * Read config YAML file into objects.
     *
     * @param string $cwd               Current working directory.
     * @return void
     */
    public static function init(string $cwd): void
    {
        // read configuration file
        $config_file = sprintf("%s/config.yml", $cwd);
        $config = yaml_parse_file($config_file);

        // create configuration objects
        self::$airtable = new Config_Airtable($config["airtable"]);
        self::$cache = new Config_Cache($config["cache"]);
        self::$dir = new Config_Dir($cwd);
        self::$events = new Config_Events($config["events"]);
        self::$formats = new Config_Formats($config["formats"]);
        self::$general = new Config_General($config["general"]);
        self::$login = new Config_Login($config["login"]);
        self::$prayer = new Config_Prayer($config["prayer"]);
        self::$rota = new Config_Rota($config["rota"]);
    }
}
