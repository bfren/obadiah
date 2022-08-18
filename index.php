<?php

namespace Feeds;

spl_autoload_register(function ($class) {
    $path = str_replace('\\', '/', $class) . ".class.php";
    $inc = str_replace("feeds", "inc", strtolower($path));
    require_once($inc);
});

// run preflight checks and load config
$base = Base::preflight(getcwd());

// create cache
$cache = new Cache\Cache($base->dir_cache, 600);

// get rota
$rota = $cache->get_rota(function() use ($base) {
    return Rota\Rota::load_csv($base);
});

// get lectionary
$lectionary = $cache->get_lectionary(function() use ($base) {
    return Lectionary\Lectionary::load_airtable($base);
});

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
