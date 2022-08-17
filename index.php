<?php

namespace Feeds;

spl_autoload_register(function ($class) {
    $path = str_replace('\\', '/', $class) . ".class.php";
    $inc = str_replace("feeds", "inc", strtolower($path));
    require_once($inc);
});

// run preflight checks and load config
$base = Base::preflight(getcwd());

// load rota
$rota = Rota\Rota::load_csv($base);

// load lectionary
$lectionary = Lectionary\Lectionary::load_airtable($base);
print_r($lectionary);

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
