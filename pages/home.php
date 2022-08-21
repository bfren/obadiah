<?php

namespace Feeds\Pages;

use DateInterval;
use DateTimeImmutable;
use Feeds\App;
use Feeds\Config\Config as C;

App::check();

// output header
$title = "Home";
require_once("parts/header.php");

?>

<h2 class="border-bottom">Welcome</h2>
<p>These pages house the various feeds generated from Church Suite.</p>

<h3>Rota</h3>
<?php
    $this_week_from = new DateTimeImmutable();
    $this_week = array(
        "from" => $this_week_from->format(C::$formats->sortable_date),
        "to" => $this_week_from->add(new DateInterval("P7D"))->format(C::$formats->sortable_date)
    );
?>
<p>To view this week's services, please click <a href="/rota/?<?php echo http_build_query($this_week); ?>">here</a>.</p>

<h4>Printable Rotas</h4>
<p>The following links will give you quick and printable rotas for upcoming services (for best results print in landscape format).</p>

<?php
    $sunday_ten_thirty_from = new DateTimeImmutable("next Sunday");
    $sunday_ten_thirty = array(
        "day" => 7, // Sunday
        "start" => "10:30",
        "from" => $sunday_ten_thirty_from->format(C::$formats->sortable_date),
        "to" => $sunday_ten_thirty_from->add(new DateInterval("P27D"))->format(C::$formats->sortable_date)
    );
?>
<p><a href="/rota/print/?<?php echo http_build_query($sunday_ten_thirty); ?>">Sunday 10:30 servies for the next four weeks</a></p>

<?php
    $wednesday_eight_oclock_from = new DateTimeImmutable("next Wednesday");
    $wednesday_eight_oclock = array(
        "day" => 3, // Wednesday
        "start" => "08:00",
        "from" => $wednesday_eight_oclock_from->format(C::$formats->sortable_date),
        "to" => $wednesday_eight_oclock_from->add(new DateInterval("P4M"))->format(C::$formats->sortable_date)
    );
?>
<p><a href="/rota/print/?<?php echo http_build_query($wednesday_eight_oclock); ?>">Wednesday Morning Prayer for the next four months</a></p>

<?php require_once("parts/footer.php"); ?>
