<?php

namespace Obadiah;

use Obadiah\Cache\Cache;
use Obadiah\Config\Config as C;
use Obadiah\Request\Request;
use Obadiah\Router\Router;
use SplFileInfo;

class App
{
    /**
     * Check constant.
     */
    private const CHECK = "CHECK";

    /**
     * Application version - this is set using the version in the source / container image VERSION file (see init).
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
        // ensure we are running on PHP 8.3
        version_compare(PHP_VERSION, "8.3.0", ">=") || self::die("This application requires at least PHP 8.3.");

        // get current working directory
        $cwd = __DIR__;

        // start session
        session_start();

        // each PHP script checks if this is defined to ensure incorrect access is denied
        define(self::CHECK, true);

        // automatically load class definitions from classes directory
        spl_autoload_register(function ($class) use ($cwd) {
            $path = sprintf("%s/%s.class.php", $cwd, str_replace(array("\\", "_", "obadiah/pages", "obadiah"), array("/", "-", "pages", "classes"), strtolower($class)));
            require_once $path;
        });

        // read application version
        $image_version = new SplFileInfo("/etc/bf/VERSION");
        $source_version = new SplFileInfo(sprintf("%s/../VERSION", $cwd));
        if ($image_version->isFile()) {
            self::$version = file_get_contents($image_version->getRealPath());
        } else if ($source_version->isFile()) {
            self::$version = file_get_contents($source_version->getRealPath());
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
        require_once "functions/log.php";
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
