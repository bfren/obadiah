<?php

namespace Obadiah;

use Obadiah\Cache\Cache;
use Obadiah\Config\Config as C;
use Obadiah\Helpers\IO;
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
     * @param bool $is_http             Whether or not the app is being loaded via HTTP.
     * @return void
     */
    public static function init(bool $is_http = true): void
    {
        // ensure we are running on PHP 8.3
        version_compare(PHP_VERSION, "8.3.0", ">=") || self::die("This application requires at least PHP 8.3.");

        // get current working directory
        $cwd = __DIR__;

        // start session unless running as CLI
        if ($is_http) {
            session_start();
        }

        // each PHP script checks if this is defined to ensure incorrect access is denied
        define(self::CHECK, true);

        // automatically load class definitions from classes directory
        spl_autoload_register(function ($class) use ($cwd) {
            $search = [0 => "\\", 1 => "_", 2 => "obadiah/api", 3 => "obadiah/pages", 4 => "obadiah"];
            $replace = [0 => "/", 1 => "-", 2 => "api", 3 => "pages", 4 => "classes"];
            $path = sprintf("%s/%s.class.php", $cwd, str_replace($search, $replace, strtolower($class)));
            require_once $path;
        });

        // read application version
        $image_version = new SplFileInfo("/etc/bf/VERSION");
        $source_version = new SplFileInfo(sprintf("%s/../VERSION", $cwd));
        if ($image_version->isFile()) {
            self::$version = IO::file_get_contents($image_version);
        } else if ($source_version->isFile()) {
            self::$version = IO::file_get_contents($source_version);
        }

        // load configuration
        C::init($cwd);

        // initialise cache
        Cache::init(C::$dir->cache, C::$cache->duration_in_seconds);

        // initialise HTTP Request / Router
        if ($is_http) {
            Request::init();
            Router::init();
        }

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
     * @return never
     */
    public static function die(string $message, mixed ...$args): void
    {
        printf($message, ...$args);
        exit;
    }
}
