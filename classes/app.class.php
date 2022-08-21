<?php

namespace Feeds;

use Feeds\Config\Config;
use Feeds\Request\Request;

class App
{
    /**
     * Initialise application - register autoloader - setup Request, etc.
     *
     * @param string $cwd               Main script working directory.
     * @return void
     */
    public static function init(string $cwd)
    {
        // start session
        session_start();

        // each PHP script checks if this is defined to ensure incorrect access is denied
        define("IDX", true);

        // automatically load class definitions from inc directory
        spl_autoload_register(function ($class) {
            $path = sprintf("%s.class.php", str_replace(array("\\", "_"), array("/", "-"), $class));
            $inc = str_replace("feeds", "classes", strtolower($path));
            require_once($inc);
        });

        // load configuration
        Config::init($cwd);

        // initialise request variables
        Request::init($cwd);
    }
}
