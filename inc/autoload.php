<?php

// each PHP script checks if this is defined to ensure incorrect access is denied
define("IDX", true);

// automatically load class definitions from inc directory
spl_autoload_register(function ($class) {
    $path = sprintf("%s.class.php", str_replace(array("\\", "_"), array("/", "-"), $class));
    $inc = str_replace("feeds", "inc", strtolower($path));
    require_once($inc);
});
