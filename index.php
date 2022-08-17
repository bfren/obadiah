<?php

namespace ChurchSuiteFeeds;

spl_autoload_register(function ( $class ) {
    $path = str_replace( '\\', '/', $class ) . ".class.php";
    $inc = str_replace( "churchsuitefeeds", "inc", strtolower( $path ) );
    require_once( $inc );
});

// run preflight checks
$base = Base::preflight( getcwd() );

// load rota files
$rota = Rota\Rota::load_csv( $base->dir_rota );

// load lectionary files
$lectionary = Lectionary\Lectionary::load_csv( $base->dir_lectionary );
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
