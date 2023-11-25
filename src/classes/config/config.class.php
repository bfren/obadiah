<?php

namespace Feeds\Config;

use Feeds\App;
use Feeds\Helpers\Arr;
use SplFileInfo;

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
     * Baserow config object.
     *
     * @var Config_Baserow
     */
    public static Config_Baserow $baserow;

    /**
     * Cache config object.
     *
     * @var Config_Cache
     */
    public static Config_Cache $cache;

    /**
     * Church Suite config object.
     *
     * @var Config_ChurchSuite
     */
    public static Config_ChurchSuite $churchsuite;

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
     * Refresh config object.
     *
     * @var Config_Refresh
     */
    public static Config_Refresh $refresh;

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
        // get path to data directory
        $data_dir_path = trim(file_get_contents(sprintf("%s/_path_to_data_dir_", $cwd)));
        $data_dir = new SplFileInfo($data_dir_path);
        if(!$data_dir->isDir()) {
            App::die("Unable to find data directory at '%s'.", $data_dir->getRealPath());
        }

        // ensure config file exists
        $config_file_path = sprintf("%s/config.yml", $data_dir);
        $config_file = new SplFileInfo($config_file_path);
        if(!$config_file->isFile()) {
            App::die("Unable to find configuration file at '%s' - see installation instructions.", $config_file->getRealPath());
        }

        // read configuration file
        $config = yaml_parse_file($config_file);

        // create configuration objects
        self::$airtable = new Config_Airtable(Arr::get($config, "airtable", array()));
        self::$baserow = new Config_Baserow(Arr::get($config, "baserow", array()));
        self::$cache = new Config_Cache(Arr::get($config, "cache", array()));
        self::$churchsuite = new Config_ChurchSuite(Arr::get($config, "churchsuite", array()));
        self::$dir = new Config_Dir($cwd, $data_dir);
        self::$events = new Config_Events(Arr::get($config, "events", array()));
        self::$formats = new Config_Formats(Arr::get($config, "formats", array()));
        self::$general = new Config_General(Arr::get($config, "general", array()));
        self::$login = new Config_Login(Arr::get($config, "login", array()));
        self::$prayer = new Config_Prayer(Arr::get($config, "prayer", array()));
        self::$refresh = new Config_Refresh(Arr::get($config, "refresh", array()));
        self::$rota = new Config_Rota(Arr::get($config, "rota", array()));
    }
}
