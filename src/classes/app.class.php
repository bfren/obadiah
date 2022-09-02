<?php

namespace Feeds;

use Feeds\Cache\Cache;
use Feeds\Config\Config as C;
use Feeds\Request\Request;

class App
{
    /**
     * Check constant.
     */
    private const CHECK = "CHECK";

    /**
     * Initialise application - register autoloader - setup Request, etc.
     *
     * @param string $cwd               Main script working directory.
     * @return void
     */
    public static function init(string $cwd): void
    {
        // start session
        session_start();

        // each PHP script checks if this is defined to ensure incorrect access is denied
        define(self::CHECK, true);

        // automatically load class definitions from classes directory
        spl_autoload_register(function ($class) use ($cwd) {
            $path = sprintf("%s/%s.class.php", $cwd, str_replace(array("\\", "_", "feeds"), array("/", "-", "classes"), strtolower($class)));
            require_once($path);
        });

        // load configuration
        C::init($cwd);

        // initialise request variables
        Request::init($cwd);

        // initialise cache
        Cache::init(C::$dir->cache, C::$cache->duration_in_seconds);
    }

    /**
     * Ensure the check constant has been defined, which means the app has been loaded correctly -
     * if it hasn't, die with an error message.
     *
     * @return void
     */
    public static function check(): void
    {
        defined(self::CHECK) || die("Nice try.");
    }
}
