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
     * Config file path format.
     *
     * @var string
     */
    public const CONFIG_FILE_PATH = "%s/config.yml";

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

        // store dir config - this should not be reloaded after init
        self::$dir = new Config_Dir($cwd, $data_dir);

        // read configuration file and store values
        $config = self::read_config_file();
        self::store_config($config);
    }

    /**
     * Get a handle object for the config file - if it doesn't exist, the App will die.
     *
     * @return SplFileInfo                          Config file object.
     */
    private static function get_config_file(): SplFileInfo
    {
        // ensure config file exists
        $config_file_path = sprintf(self::CONFIG_FILE_PATH, self::$dir->data_dir);
        $config_file = new SplFileInfo($config_file_path);
        if (!$config_file->isFile()) {
            App::die("Unable to find configuration file at '%s' - see installation instructions.", $config_file->getRealPath());
        }

        // return file handle
        return $config_file;
    }

    /**
     * Read YAML configuration file into an array.
     *
     * @return mixed[]                              Configuration object.
     */
    private static function read_config_file(): array
    {
        $config_file = self::get_config_file();
        return yaml_parse_file($config_file);
    }

    /**
     * Save current config to YAML configuration file.
     *
     * @return void
     */
    private static function save_config_file(): void
    {
        // create config object
        $config = [
            "general" => self::$general->as_array(),
            "baserow" => self::$baserow->as_array(),
            "cache" => self::$cache->as_array(),
            "churchsuite" => self::$churchsuite->as_array(),
            "events" => self::$events->as_array(),
            "formats" => self::$formats->as_array(),
            "login" => self::$login->as_array(),
            "prayer" => self::$prayer->as_array(),
            "refresh" => self::$refresh->as_array(),
            "rota" => self::$rota->as_array()
        ];

        // save as yaml file
        $config_file = self::get_config_file();
        yaml_emit_file($config_file->getFilename(), $config);
    }

    /**
     * Store configuration from value of arrays.
     *
     * @param array<string, mixed> $config          Config values.
     * @param bool $save_file                       If true, the config will be persisted to YAML configuration file.
     * @return void
     */
    public static function store_config(array $config, bool $save_file = false): void
    {
        // store values
        self::$baserow = new Config_Baserow(Arr::get_required($config, "baserow"));
        self::$cache = new Config_Cache(Arr::get_required($config, "cache"));
        self::$churchsuite = new Config_ChurchSuite(Arr::get_required($config, "churchsuite"));
        self::$events = new Config_Events(Arr::get_required($config, "events"));
        self::$formats = new Config_Formats(Arr::get_required($config, "formats"));
        self::$general = new Config_General(Arr::get_required($config, "general"));
        self::$login = new Config_Login(Arr::get_required($config, "login"));
        self::$prayer = new Config_Prayer(Arr::get_required($config, "prayer"));
        self::$refresh = new Config_Refresh(Arr::get_required($config, "refresh"));
        self::$rota = new Config_Rota(Arr::get_required($config, "rota"));

        // save file
        if ($save_file) {
            self::save_config_file();
        }
    }
}
