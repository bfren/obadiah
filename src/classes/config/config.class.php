<?php

namespace Obadiah\Config;

use Obadiah\App;
use Obadiah\Helpers\Arr;
use Obadiah\Helpers\IO;
use SplFileInfo;

App::check();

class Config
{
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
     * @param string $cwd                           Current working directory.
     * @return void
     */
    public static function init(string $cwd): void
    {
        // get path to data directory
        $data_dir_path = IO::file_get_contents(sprintf("%s/_path_to_data_dir_", $cwd));
        $data_dir = new SplFileInfo($data_dir_path);
        if (!$data_dir->isDir()) {
            App::die("Unable to find data directory at '%s'.", $data_dir->getRealPath());
        }

        // ensure config file exists
        $config_file_path = sprintf("%s/config.yml", $data_dir);
        $config_file = new SplFileInfo($config_file_path);
        if (!$config_file->isFile()) {
            App::die("Unable to find configuration file at '%s' - see installation instructions.", $config_file->getRealPath());
        }

        // read configuration file
        $config = self::read_config($config_file);

        // create configuration objects
        self::$baserow = new Config_Baserow(Arr::get_required($config, "baserow"));
        self::$cache = new Config_Cache(Arr::get_required($config, "cache"));
        self::$churchsuite = new Config_ChurchSuite(Arr::get_required($config, "churchsuite"));
        self::$dir = new Config_Dir($cwd, $data_dir);
        self::$events = new Config_Events(Arr::get_required($config, "events"));
        self::$formats = new Config_Formats(Arr::get_required($config, "formats"));
        self::$general = new Config_General(Arr::get_required($config, "general"));
        self::$login = new Config_Login(Arr::get_required($config, "login"));
        self::$prayer = new Config_Prayer(Arr::get_required($config, "prayer"));
        self::$refresh = new Config_Refresh(Arr::get_required($config, "refresh"));
        self::$rota = new Config_Rota(Arr::get_required($config, "rota"));
    }

    /**
     * Read YAML configuration file into an array.
     *
     * @param SplFileInfo $config_file              Config file object.
     * @return mixed[]                              Configuration object.
     */
    private static function read_config(SplFileInfo $config_file): array
    {
        return yaml_parse_file($config_file);
    }
}
