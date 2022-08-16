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
$rota = Rota\Rota::load( $base->dir_rota );
print_r($rota);

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
