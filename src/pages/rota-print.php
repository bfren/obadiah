<?php

namespace Feeds\Pages;

use Feeds\App;
use Feeds\Helpers\Arr;
use Feeds\Rota\Builder;

App::check();

/** @var array $filters */
/** @var \Feeds\Rota\Combined_Day[] $combined_days */

// look for standard filters
$time = Arr::get($filters, "time");
$day = Builder::get_day(Arr::get($filters, "day", 0));
$person = Arr::get($filters, "person");

// output header
$title = "Rota";
require_once("parts/header-print.php"); ?>

<h2>
    Christ Church Rota
    <?php if ($time) _e(" - %s", $time); ?>
    <?php if ($day) _e($day); ?>
    <?php if ($person) _e(" - %s", $person); ?>
</h2>
<?php require_once("parts/rota-services-print.php"); ?>

<?php require_once("parts/footer-print.php"); ?>
