<?php

namespace Feeds;

// automatically load class definitions from inc directory
spl_autoload_register(function ($class) {
    $path = str_replace('\\', '/', $class) . ".class.php";
    $inc = str_replace("feeds", "inc", strtolower($path));
    require_once($inc);
});

// run preflight checks and load config
$base = new Base(getcwd());

// create cache
$cache = new Cache\Cache($base->dir_cache, 5);

// get rota
$rota = $cache->get_rota(function() use ($base) {
    return new Rota\Rota($base);
});

// get lectionary
$lectionary = $cache->get_lectionary(function() use ($base) {
    return new Lectionary\Lectionary($base);
});

// apply filters
$services = $rota->apply_filters($_GET);

?>
<!DOCTYPE html>
<html>

<head>
    <title>Church Suite Feeds</title>
</head>

<body>
    <h1>Church Suite Feeds</h1>
</body>

</html>
