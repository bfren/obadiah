<?php

namespace Feeds;

use Feeds\Cache\Cache;
use Feeds\Config\Config as C;
use Feeds\Request\Request;
use Feeds\Router\Router;
use SplFileInfo;
use SplFileObject;

class App
{
    /**
     * Check constant.
     */
    private const CHECK = "CHECK";

    /**
     * Application version.
     *
     * @var string
     */
    public static string $version = "0.1";

    /**
     * Initialise application - register autoloader - setup Request, etc.
     *
     * @return void
     */
    public static function init(): void
    {
        // get current working directory
        $cwd = __DIR__;

        // start session
        session_start();

        // each PHP script checks if this is defined to ensure incorrect access is denied
        define(self::CHECK, true);

        // automatically load class definitions from classes directory
        spl_autoload_register(function ($class) use ($cwd) {
            $path = sprintf("%s/%s.class.php", $cwd, str_replace(array("\\", "_", "feeds/pages", "feeds"), array("/", "-", "pages", "classes"), strtolower($class)));
            require_once $path;
        });

        // read application version
        $version_file = new SplFileInfo(sprintf("%s/VERSION", $cwd));
        if($version_file->isFile()) {
            self::$version = file_get_contents($version_file->getRealPath());
        }

        // load configuration
        C::init($cwd);

        // initialise request variables
        Request::init($cwd);

        // initialise cache
        Cache::init(C::$dir->cache, C::$cache->duration_in_seconds);

        // initialise router
        Router::init();

        // require function scripts
        require_once "functions/escape.php";
    }

    /**
     * Ensure the check constant has been defined, which means the app has been loaded correctly -
     * if it hasn't, die with an error message.
     *
     * @return void
     */
    public static function check(): void
    {
        defined(self::CHECK) || self::die("Nice try.");
    }

    /**
     * Ouput message and exit (equivalent of die).
     *
     * @param string $message           Output (error) message.
     * @return void
     */
    public static function die(string $message, mixed ...$args): void
    {
        // if arguments have been provided, use sprintf
        if (count($args) > 0) {
            $message = sprintf($message, ...$args);
        }

        // output message and exit
        print_r($message);
        exit;
    }
}
