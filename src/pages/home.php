<?php

namespace Feeds\Pages;

use DateInterval;
use DateTimeImmutable;
use Feeds\App;
use Feeds\Config\Config as C;
use Feeds\Rota\Rota;

App::check();

// output header
$title = "Home";
$subtitle = "These pages house the various feeds generated from Church Suite.";
require_once "parts/header.php";

?>

<h2>Rota</h2>
<?php
    $this_week_from = new DateTimeImmutable();
    $this_week = array(
        "from" => $this_week_from->format(C::$formats->sortable_date),
        "to" => $this_week_from->add(new DateInterval("P7D"))->format(C::$formats->sortable_date)
    );
?>
<p>To view this week&rsquo;s services, please click <a href="/rota/?<?php _e(http_build_query($this_week)); ?>">here</a>.</p>

<h3>Printable</h3>
<p>The following links will give you quick and printable rotas for upcoming services.</p>
<p><a href="/rota/notices/?<?php _e(http_build_query(Rota::upcoming_ten_thirty())); ?>">Sunday 10:30 services for the next four weeks</a></p>
<p><a href="/rota/print/?<?php _e(http_build_query(Rota::wednesday_morning_prayer())); ?>">Wednesday Morning Prayer for the next three months</a></p>

<h2>Refresh</h2>
<?php
    $refresh = array(
        "api" => C::$login->api
    );
?>
<p>Use <a href="/refresh/ics/?<?php _e(http_build_query($refresh)); ?>">this link</a> to subscribe to the Refresh calendar feed.</p>

<?php require_once "parts/footer.php"; ?>
